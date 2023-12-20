<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Wordless\Application\Commands\Traits\ForceMode;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\Traits\WunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToChangePathPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToRewriteDotEnvFile;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Listeners\CustomLoginUrl\Contracts\BaseListener as CustomLoginUrl;
use Wordless\Application\Mounters\Stub\RobotsTxtStubMounter;
use Wordless\Application\Mounters\Stub\WpConfigStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;
use Wordless\Wordpress\Enums\StartOfWeek;

class WordlessInstall extends ConsoleCommand
{
    use ForceMode;
    use RunWpCliCommand;

    final public const COMMAND_NAME = 'wordless:install';
    final protected const NO_ASK_MODE = 'no-ask';
    private const WORDPRESS_SALT_FILLABLE_VALUES = [
        '#AUTH_KEY',
        '#SECURE_AUTH_KEY',
        '#LOGGED_IN_KEY',
        '#NONCE_KEY',
        '#AUTH_SALT',
        '#SECURE_AUTH_SALT',
        '#LOGGED_IN_SALT',
        '#NONCE_SALT',
    ];
    private const WORDPRESS_SALT_URL_GETTER = 'https://api.wordpress.org/secret-key/1.1/salt/';

    protected static $defaultName = self::COMMAND_NAME;

    private array $fresh_new_env_content;
    private QuestionHelper $questionHelper;
    private array $wp_languages;
    private bool $maintenance_mode;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Install project.';
    }

    /**
     * @return int
     * @throws ClientExceptionInterface
     * @throws ExceptionInterface
     * @throws FailedToChangePathPermissions
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToDeletePath
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToGetFileContent
     * @throws FailedToRewriteDotEnvFile
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    protected function runIt(): int
    {
        $this->resolveForceMode()
            ->resolveDotEnv()
            ->loadWpLanguages()
            ->createWpConfigFromStub()
            ->createRobotsTxtFromStub()
            ->createWpDatabase()
            ->coreSteps()
            ->resolveWpConfigChmod();

        return Command::SUCCESS;
    }

    protected function help(): string
    {
        return 'Completely installs this project calling WP-CLI.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            $this->mountAllowRootModeOption(),
            $this->mountForceModeOption('Forces a project installation.'),
            new OptionDTO(
                self::NO_ASK_MODE,
                'Don\'t ask for any input while running.',
                mode: OptionMode::no_value,
            ),
        ];
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    protected function setup(InputInterface $input, OutputInterface $output): void
    {
        parent::setup($input, $output);

        $this->questionHelper = $this->getHelper('question');
        $this->maintenance_mode = false;
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function activateWpTheme(): static
    {
        $this->runWpCliCommand(
            "theme activate {$this->getEnvVariableByKey('WP_THEME', 'wordless')}"
        );

        return $this;
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function activateWpPlugins(): static
    {
        $this->runWpCliCommand('plugin activate --all');

        return $this;
    }

    /**
     * @return void
     * @throws ExceptionInterface
     * @throws PathNotFoundException
     * @throws WpCliCommandReturnedNonZero
     */
    private function applyAdminConfiguration(): void
    {
        $this->runWpCliCommand('option update date_format '
            . Config::tryToGetOrDefault('admin.datetime.date_format', 'Y-m-d'));
        $this->runWpCliCommand('option update time_format '
            . Config::tryToGetOrDefault('admin.datetime.time_format', 'H:i'));
        $this->runWpCliCommand('option update '
            . StartOfWeek::KEY
            . ' '
            . Config::tryToGetOrDefault('admin.' . StartOfWeek::KEY, StartOfWeek::sunday->value));
    }

    private function ask(string $question, $default = null)
    {
        return $this->questionHelper->ask($this->input, $this->output, new Question($question, $default));
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     * @throws PathNotFoundException
     * @throws WpCliCommandReturnedNonZero
     */
    private function coreSteps(): static
    {
        $this->switchingMaintenanceMode(true);

        try {
            $this->performUrlDatabaseFix()
                ->flushWpRewriteRules()
                ->activateWpTheme()
                ->activateWpPlugins()
                ->installWpLanguages()
                ->makeWpBlogPublic()
                ->databaseUpdate()
                ->generateSymbolicLinks()
                ->runMigrations()
                ->syncRoles()
                ->createCache()
                ->applyAdminConfiguration();
        } finally {
            $this->switchingMaintenanceMode(false);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     */
    private function createCache(): static
    {
        $this->callConsoleCommand(CreateInternalCache::COMMAND_NAME, output: $this->output);

        return $this;
    }

    /**
     * @return $this
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     */
    private function createRobotsTxtFromStub(): static
    {
        $robotStubMounter = new RobotsTxtStubMounter(
            ProjectPath::public()
            . DIRECTORY_SEPARATOR
            . RobotsTxtStubMounter::STUB_FINAL_FILENAME
        );
        $custom_login_url = Config::tryToGetOrDefault(
            'wordpress.admin.' . CustomLoginUrl::WP_CUSTOM_LOGIN_URL_KEY,
            false
        );

        $robotStubMounter->setReplaceContentDictionary([
            '{APP_URL}' => Str::finishWith($this->getEnvVariableByKey('APP_URL', ''), '/'),
            '#custom_login_url' => $custom_login_url ? "Disallow: /$custom_login_url/" : ''
        ])->mountNewFile();

        return $this;
    }

    /**
     * @return $this
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     */
    private function createWpConfigFromStub(): static
    {
        WpConfigStubMounter::make(ProjectPath::wpCore() . '/wp-config.php')->mountNewFile();

        return $this;
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function createWpDatabase(): static
    {
        $database_username = $this->getEnvVariableByKey('DB_USER');
        $database_password = $this->getEnvVariableByKey('DB_PASSWORD');

        if ($this->runWpCliCommand(
                "db check --dbuser=$database_username --dbpass=$database_password",
                true
            ) == 0) {
            $this->writelnCommentWhenVerbose('WordPress Database already created, skipping.');

            return $this;
        }

        $this->runWpCliCommand("db create --dbuser=$database_username --dbpass=$database_password");

        return $this;
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function databaseUpdate(): static
    {
        $this->runWpCliCommand('core update-db', true);

        return $this;
    }

    /**
     * @param string $dot_env_filepath
     * @return void
     * @throws ClientExceptionInterface
     * @throws DirectoryFiles\Exceptions\FailedToGetFileContent
     * @throws FailedToRewriteDotEnvFile
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function fillDotEnv(string $dot_env_filepath): void
    {
        if (($dot_env_content = $this->guessAndResolveDotEnvWpSaltVariables(
                $dot_env_original_content = DirectoryFiles::getFileContent($dot_env_filepath)
            )) !== $dot_env_original_content) {
            Environment::rewriteDotEnvFile($dot_env_filepath, $dot_env_content);
        }

        // populates an internal array with env variables freshly new.
        $this->fresh_new_env_content = (new Dotenv)->parse($dot_env_content);
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function flushWpRewriteRules(): static
    {
        $permalink_structure = $this->getEnvVariableByKey('WP_PERMALINK', '/%postname%/');

        $this->runWpCliCommand("rewrite structure '$permalink_structure' --hard");
        $this->runWpCliCommand('rewrite flush --hard');

        return $this;
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     */
    private function generateSymbolicLinks(): static
    {
        $this->callConsoleCommand(
            GeneratePublicWordpressSymbolicLinks::COMMAND_NAME,
            output: $this->output
        );

        return $this;
    }

    private function getDotEnvNotFilledVariables(string $dot_env_content): array
    {
        preg_match_all('/^(.+)=(#\1)$/m', $dot_env_content, $not_filled_variables_regex_result);
        // Getting Regex result (#\1) group or leading to an empty array
        return $not_filled_variables_regex_result[2] ?? [];
    }

    private function getEnvVariableByKey(string $key, $default = null)
    {
        return $this->fresh_new_env_content[$key] ?? Environment::get($key, $default);
    }

    private function getWpLanguages(): array
    {
        return $this->wp_languages;
    }

    /**
     * @return $this
     * @throws PathNotFoundException
     */
    private function loadWpLanguages(): static
    {
        $this->wp_languages = explode(
            ',',
            Config::tryToGetOrDefault('wordpress.languages', ['en_US'])
        );

        return $this;
    }

    /**
     * @param string $dot_env_content
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function guessAndResolveDotEnvWpSaltVariables(string $dot_env_content): string
    {
        if (!Str::contains($dot_env_content, self::WORDPRESS_SALT_FILLABLE_VALUES)) {
            return $dot_env_content;
        }

        $wp_salt_response = $this->wrapScriptWithMessages(
            'Retrieving WP SALTS at ' . self::WORDPRESS_SALT_URL_GETTER . '...',
            function () {
                return HttpClient::create()->request(
                    'GET',
                    self::WORDPRESS_SALT_URL_GETTER
                )->getContent();
            },
            ' Done!',
            true
        );

        preg_match_all(
            '/define\(\'(.+)\',.+\'(.+)\'\);/',
            $wp_salt_response,
            $parse_wp_salt_response_regex_result
        );

        return str_replace(
            array_map(function ($env_variable_name) {
                return "#$env_variable_name";
            }, $parse_wp_salt_response_regex_result[1] ?? []),
            array_map(function ($salt_value) {
                return "\"$salt_value\"";
            }, $parse_wp_salt_response_regex_result[2] ?? []),
            $dot_env_content
        );
    }

    /**
     * @param string $language
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function installWpCoreLanguage(string $language): void
    {
        if ($this->runWpCliCommand("language core is-installed $language", true) == 0) {
            $this->writelnInfoWhenVerbose("WordPress Core Language $language already installed, updating.");

            $this->runWpCliCommand('language core update', true);
            $this->runWpCliCommand("site switch-language $language", true);

            return;
        }

        $this->runWpCliCommand("language core install $language --activate");
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function installWpLanguages(): static
    {
        if (empty($wp_languages = $this->getWpLanguages())) {
            $this->writelnWarning('Environment variable WP_LANGUAGES has no value. Skipping language install.');

            return $this;
        }

        $this->installWpCoreLanguage($wp_languages[0]);

        foreach ($wp_languages as $language) {
            $this->installWpPluginsLanguage($language);
        }

        return $this;
    }

    /**
     * @param string $language
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function installWpPluginsLanguage(string $language): void
    {
        $this->runWpCliCommand("language plugin install $language --all", true);
        $this->runWpCliCommand("language plugin update $language --all", true);
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function makeWpBlogPublic(): static
    {
        $blog_public = $this->getEnvVariableByKey('APP_ENV') === Environment::PRODUCTION ? '1' : '0';

        $this->runWpCliCommand("option update blog_public $blog_public");

        return $this;
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function performUrlDatabaseFix(): static
    {
        $app_url = $this->getEnvVariableByKey('APP_URL');

        $this->runWpCliCommand("option update siteurl $app_url/wp-core/");
        $this->runWpCliCommand("option update home $app_url");

        return $this;
    }

    /**
     * @return $this
     * @throws ClientExceptionInterface
     * @throws FailedToGetFileContent
     * @throws FailedToRewriteDotEnvFile
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface,
     */
    private function resolveDotEnv(): static
    {
        $this->fillDotEnv(ProjectPath::root('.env'));

        return $this;
    }

    /**
     * @return $this
     * @throws FailedToDeletePath
     * @throws InvalidArgumentException
     * @throws InvalidDirectory
     */
    private function resolveForceMode(): static
    {
        if ($this->isForceMode()) {
            try {
                $wp_core_path = ProjectPath::wpCore();
                $gitignore_wp_core_filepath = ProjectPath::wpCore('.gitignore');
                $this->wrapScriptWithMessages(
                    "Deleting everything inside $wp_core_path but $gitignore_wp_core_filepath...",
                    function () use ($wp_core_path, $gitignore_wp_core_filepath) {
                        DirectoryFiles::recursiveDelete($wp_core_path, [$gitignore_wp_core_filepath], false);
                    }
                );

                $robots_txt_filepath = ProjectPath::public('robots.txt');

                $this->wrapScriptWithMessages(
                    "Deleting $robots_txt_filepath...",
                    function () use ($robots_txt_filepath) {
                        DirectoryFiles::delete($robots_txt_filepath);
                    }
                );
            } catch (PathNotFoundException $exception) {
                $this->writelnCommentWhenVerbose("{$exception->getMessage()} Skipped from force mode.");
            }
        }

        return $this;
    }

    /**
     * @return void
     * @throws FailedToChangePathPermissions
     * @throws PathNotFoundException
     */
    private function resolveWpConfigChmod(): void
    {
        if ($this->getEnvVariableByKey('APP_ENV') === Environment::PRODUCTION) {

            DirectoryFiles::changePermissions(ProjectPath::wpCore('wp-config.php'), 0660);
        }
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     */
    private function runMigrations(): static
    {
        $this->callConsoleCommand(Migrate::COMMAND_NAME, output: $this->output);

        return $this;
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     */
    private function syncRoles(): static
    {
        $this->callConsoleCommand(SyncRoles::COMMAND_NAME, output: $this->output);

        return $this;
    }

    /**
     * @param bool $switch
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function switchingMaintenanceMode(bool $switch): void
    {
        $switch_string = $switch ? 'activate' : 'deactivate';

        if ($this->maintenance_mode === $switch) {
            $this->writelnComment("Maintenance mode already {$switch_string}d. Skipping...");

            return;
        }

        $this->runWpCliCommand("maintenance-mode $switch_string");

        $this->maintenance_mode = $switch;
    }
}
