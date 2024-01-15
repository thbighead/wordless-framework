<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Migrations\Migrate;
use Wordless\Application\Commands\Traits\ForceMode;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\WordlessInstall\Traits\ForFramework;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToChangePathPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToPutFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToRewriteDotEnvFile;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Listeners\CustomAdminUrl\Contracts\BaseListener as CustomLoginUrl;
use Wordless\Application\Mounters\Stub\RobotsTxtStubMounter;
use Wordless\Application\Mounters\Stub\WordlessPluginStubMounter;
use Wordless\Application\Mounters\Stub\WpConfigStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;
use Wordless\Wordpress\Enums\StartOfWeek;

class WordlessInstall extends ConsoleCommand
{
    use ForceMode;
    use ForFramework;
    use RunWpCliCommand;

    final public const COMMAND_NAME = 'wordless:install';
    final public const TEMP_MAIL = 'temp@mail.not.real';
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

    private array $fresh_new_env_content;
    private array $wp_languages;
    private bool $maintenance_mode;

    /**
     * @param int $signal
     * @return void
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    public function handleSignal(int $signal): void
    {
        parent::handleSignal($signal);

        $this->switchingMaintenanceMode(false);
    }

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
     * @throws CliReturnedNonZero
     * @throws ClientExceptionInterface
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws FailedToChangePathPermissions
     * @throws FailedToCopyFile
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToDeletePath
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToGetFileContent
     * @throws FailedToPutFileContent
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
            ->createWordlessPluginFromStub()
            ->createWpDatabase()
            ->coreSteps()
            ->runMigrations()
            ->syncRoles()
            ->createCache()
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
        ];
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function setup(InputInterface $input, OutputInterface $output): void
    {
        parent::setup($input, $output);

        $this->maintenance_mode = false;
    }

    /**
     * @return $this
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToPutFileContent
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @throws WpCliCommandReturnedNonZero
     */
    private function activateWpTheme(): static
    {
        if (Environment::isFramework()) {
            $this->generateEmptyWordlessTheme();
        }

        $this->runWpCliCommand(
            "theme activate {$this->getEnvVariableByKey('WP_THEME', 'wordless')}"
        );

        return $this;
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function activateWpPlugins(): static
    {
        $this->runWpCliCommand('plugin activate --all');

        return $this;
    }

    /**
     * @return void
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @throws WpCliCommandReturnedNonZero
     */
    private function applyAdminConfiguration(): void
    {
        $this->runWpCliCommand('option update date_format "'
            . Config::tryToGetOrDefault('wordpress.admin.datetime.date_format', 'Y-m-d')
            . '"');
        $this->runWpCliCommand('option update time_format "'
            . Config::tryToGetOrDefault('wordpress.admin.datetime.time_format', 'H:i')
            . '"');
        $this->runWpCliCommand('option update '
            . StartOfWeek::KEY
            . ' '
            . Config::tryToGetOrDefault('wordpress.admin.' . StartOfWeek::KEY, StartOfWeek::sunday->value));
    }

    /**
     * @return $this
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     */
    private function createWordlessPluginFromStub(): static
    {
        WordlessPluginStubMounter::make(ProjectPath::wp() . '/wp-content/mu-plugins/wordless-plugin.php')
            ->mountNewFile();

        return $this;
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToPutFileContent
     * @throws InvalidArgumentException
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
                ->applyAdminConfiguration();
        } finally {
            $this->switchingMaintenanceMode(false);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws ExceptionInterface
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     */
    private function createCache(): static
    {
        $this->callConsoleCommand(CreateInternalCache::COMMAND_NAME);

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
        if (Environment::isFramework()) {
            return $this;
        }

        $robotStubMounter = new RobotsTxtStubMounter(
            ProjectPath::public()
            . DIRECTORY_SEPARATOR
            . RobotsTxtStubMounter::STUB_FINAL_FILENAME
        );

        $custom_login_url = Config::tryToGetOrDefault(
            'wordpress.admin.' . CustomLoginUrl::CUSTOM_ADMIN_URL_KEY,
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
     * @throws FailedToCopyFile
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     */
    private function createWpConfigFromStub(): static
    {
        $wp_config_destiny_path = ProjectPath::wpCore() . '/wp-config.php';

        if (Environment::isFramework()) {
            DirectoryFiles::copyFile(
                ProjectPath::root('wp-config.php'),
                $wp_config_destiny_path,
                false
            );

            return $this;
        }

        WpConfigStubMounter::make($wp_config_destiny_path)->mountNewFile();

        return $this;
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function createWpDatabase(): static
    {
        try {
            $this->runWpCliCommand('db check');
            $this->writelnCommentWhenVerbose('WordPress Database already created, skipping.');
        } catch (WpCliCommandReturnedNonZero) {
            $this->runWpCliCommand('db create');
        }

        return $this->installWpDatabaseCore();
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function databaseUpdate(): static
    {
        $this->runWpCliCommand('core update-db');

        return $this;
    }

    /**
     * @param string $dot_env_filepath
     * @return void
     * @throws ClientExceptionInterface
     * @throws FailedToGetFileContent
     * @throws FailedToRewriteDotEnvFile
     * @throws FormatException
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
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
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
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    private function generateSymbolicLinks(): static
    {
        if (Environment::isNotFramework()) {
            $this->callConsoleCommand(GeneratePublicWordpressSymbolicLinks::COMMAND_NAME);
        }

        return $this;
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
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function installWpDatabaseCore(): static
    {
        try {
            $this->runWpCliCommand('core is-installed');
            $this->writelnInfoWhenVerbose('WordPress Core Database already installed, skipping.');
        } catch (WpCliCommandReturnedNonZero) {
            $app_name = $this->getEnvVariableByKey('APP_NAME', 'Wordless App');
            $app_url = $this->getEnvVariableByKey('APP_URL', Environment::isFramework() ? '' : null);
            $app_url_with_final_slash = Str::finishWith($app_url, '/');

            $this->runWpCliCommand(
                "core install --url=$app_url_with_final_slash --locale={$this->getWpLanguages()[0]} --title=\"$app_name\" --skip-email --admin_user=temp --admin_email="
                . self::TEMP_MAIL
            );
        }

        return $this;
    }

    /**
     * @return $this
     * @throws PathNotFoundException
     */
    private function loadWpLanguages(): static
    {
        $this->wp_languages = Config::tryToGetOrDefault('wordpress.languages');

        if (empty($this->wp_languages)) {
            $this->wp_languages = ['en_US'];
        }

        return $this;
    }

    /**
     * @param string $language
     * @return void
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function installWpCoreLanguage(string $language): void
    {
        try {
            $this->runWpCliCommand("language core is-installed $language");
            $this->writelnInfoWhenVerbose("WordPress Core Language $language already installed, updating.");
        } catch (WpCliCommandReturnedNonZero) {
            $this->runWpCliCommand("language core install $language --activate");
            return;
        }

        $this->runWpCliCommand('language core update');
        $this->runWpCliCommand("site switch-language $language");
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
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
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function installWpPluginsLanguage(string $language): void
    {
        $this->runWpCliCommand("language plugin install $language --all");
        $this->runWpCliCommand("language plugin update $language --all");
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
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
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function performUrlDatabaseFix(): static
    {
        $app_url = $this->getEnvVariableByKey('APP_URL');

        $this->runWpCliCommand("option update siteurl $app_url/wp-core/");
        $this->runWpCliCommand('option update home ' . (Environment::isFramework() ? '/' : $app_url));
        $this->runWpCliCommand('db optimize');

        return $this;
    }

    /**
     * @return $this
     * @throws ClientExceptionInterface
     * @throws FailedToGetFileContent
     * @throws FailedToRewriteDotEnvFile
     * @throws FormatException
     * @throws PathNotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
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
     * @return $this
     * @throws FailedToChangePathPermissions
     * @throws PathNotFoundException
     */
    private function resolveWpConfigChmod(): static
    {
        if ($this->getEnvVariableByKey('APP_ENV') === Environment::PRODUCTION) {
            DirectoryFiles::changePermissions(ProjectPath::wpCore('wp-config.php'), 0660);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    private function runMigrations(): static
    {
        if (Environment::isNotFramework()) {
            $this->callConsoleCommand(Migrate::COMMAND_NAME);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    private function syncRoles(): static
    {
        $this->callConsoleCommand(SyncRoles::COMMAND_NAME);

        return $this;
    }

    /**
     * @param bool $switch
     * @return void
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
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
