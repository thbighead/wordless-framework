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
use Wordless\Application\Commands\Schedules\RegisterSchedules;
use Wordless\Application\Commands\Traits\ForceMode;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\WordlessInstall\Traits\ForFramework;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToChangePathPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToPutFileContent;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToRewriteDotEnvFile;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\RobotsTxtStubMounter;
use Wordless\Application\Mounters\Stub\WordlessPluginStubMounter;
use Wordless\Application\Providers\AdminCustomUrlProvider;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;
use Wordless\Wordpress\Models\User\WordlessUser;

class WordlessInstall extends ConsoleCommand
{
    use ForceMode;
    use ForFramework;
    use RunWpCliCommand;

    public const COMMAND_NAME = 'wordless:install';
    private const FIRST_ADMIN_PASSWORD = 'wordless_admin';
    private const FIRST_ADMIN_USERNAME = 'admin';
    private const MU_PLUGIN_FILE_NAME = 'wordless-plugin.php';
    private const WORDPRESS_SALT_URL_GETTER = 'https://api.wordpress.org/secret-key/1.1/salt/';

    private array $fresh_new_env_content;
    private array $wp_languages;
    private bool $maintenance_mode;

    /**
     * @param int $signal
     * @param int|false $previousExitCode
     * @return int|false
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    public function handleSignal(int $signal, int|false $previousExitCode = 0): int|false
    {
        parent::handleSignal($signal, $previousExitCode);

        $this->switchingMaintenanceMode(false);

        return $previousExitCode;
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
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws ExceptionInterface
     * @throws FailedToChangePathPermissions
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToDeletePath
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToGetFileContent
     * @throws FailedToPutFileContent
     * @throws FailedToRewriteDotEnvFile
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    protected function runIt(): int
    {
        $this->resolveForceMode()
            ->flushCache()
            ->resolveDotEnv()
            ->loadWpLanguages()
            ->createWpConfigFromStub()
            ->createRobotsTxtFromStub()
            ->createWordlessPluginFromStub()
            ->createWpDatabase()
            ->coreSteps()
            ->createCache()
            ->registerSchedules()
            ->runMigrations()
            ->syncRoles()
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
            ...$this->mountRunWpCliOptions(),
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
            'theme activate ' . Config::wordpress(Config::KEY_THEME, 'wordless')
        );

        return $this;
    }

    /**
     * @return $this
     * @throws CommandNotFoundException
     * @throws EmptyConfigKey
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @throws WpCliCommandReturnedNonZero
     */
    private function activateWpPlugins(): static
    {
        foreach (Config::wordlessPluginsOrder() as $plugin_name) {
            try {
                ProjectPath::wpPlugins($plugin_name);

                $this->runWpCliCommand("plugin activate $plugin_name");
            } catch (PathNotFoundException) {
                continue;
            }
        }

        $this->runWpCliCommand('plugin activate --all');

        return $this;
    }

    /**
     * @return void
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    private function applyAdminConfiguration(): void
    {
        $this->callConsoleCommand(ConfigureDateOptions::COMMAND_NAME);
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
        WordlessPluginStubMounter::make(ProjectPath::wpMustUsePlugins() . '/' . self::MU_PLUGIN_FILE_NAME)
            ->mountNewFile();

        return $this;
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws ExceptionInterface
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToPutFileContent
     * @throws FormatException
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
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws DotEnvNotSetException
     * @throws ExceptionInterface
     * @throws FormatException
     */
    private function createCache(): static
    {
        if ($this->getEnvVariableByKey('APP_ENV') !== Environment::LOCAL) {
            $this->callConsoleCommand(CreateInternalCache::COMMAND_NAME);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws DotEnvNotSetException
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FormatException
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

        $robotStubMounter->setReplaceContentDictionary([
            '{APP_URL}' => Str::finishWith($this->getEnvVariableByKey('APP_URL', ''), '/'),
        ])->mountNewFile();

        return $this;
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    private function createWpConfigFromStub(): static
    {
        $this->callConsoleCommand(PublishWpConfigPhp::COMMAND_NAME);

        return $this;
    }

    /**
     * @return $this
     * @throws CommandNotFoundException
     * @throws DotEnvNotSetException
     * @throws ExceptionInterface
     * @throws FormatException
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
     * @param string $filepath
     * @return void
     * @throws FailedToDeletePath
     * @throws PathNotFoundException
     */
    private function deleteFileForForceMode(string $filepath): void
    {
        $this->wrapScriptWithMessages(
            "Deleting $filepath...",
            function () use ($filepath) {
                DirectoryFiles::delete($filepath);
            }
        );
    }

    /**
     * @return $this
     * @throws FailedToDeletePath
     */
    private function deleteRobotsTxtForForceMode(): static
    {
        try {
            $this->deleteFileForForceMode(ProjectPath::public('robots.txt'));
        } catch (PathNotFoundException $exception) {
            $this->writePathNotFoundMessageForForceMode($exception);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws FailedToDeletePath
     */
    private function deleteWordlessMuPluginForForceMode(): static
    {
        try {
            $this->deleteFileForForceMode(ProjectPath::wpMustUsePlugins(self::MU_PLUGIN_FILE_NAME));
        } catch (PathNotFoundException $exception) {
            $this->writePathNotFoundMessageForForceMode($exception);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws FailedToDeletePath
     */
    private function deleteWpConfigForForceMode(): static
    {
        try {
            $this->deleteFileForForceMode(ProjectPath::wpCore('wp-config.php'));
        } catch (PathNotFoundException $exception) {
            $this->writePathNotFoundMessageForForceMode($exception);
        }

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
        if (($dot_env_content = $this->rotateDotEnvWpSaltVariables(
                $dot_env_original_content = DirectoryFiles::getFileContent($dot_env_filepath)
            )) !== $dot_env_original_content) {
            Environment::rewriteDotEnvFile($dot_env_filepath, $dot_env_content);
        }

        // populates an internal array with env variables freshly new.
        $this->fresh_new_env_content = (new Dotenv)->parse($dot_env_content);
    }

    private function firstAdminEmail(): string
    {
        try {
            $app_host = $this->getEnvVariableByKey('APP_HOST', $app_host = 'wordless.wordless');
        } catch (FormatException|DotEnvNotSetException) {
        }

        return self::FIRST_ADMIN_USERNAME . "@$app_host";
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    private function flushCache(): static
    {
        $this->callConsoleCommand(CleanInternalCache::COMMAND_NAME);

        return $this;
    }

    /**
     * @return $this
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @throws WpCliCommandReturnedNonZero
     */
    private function flushWpRewriteRules(): static
    {
        $permalink_structure = Config::wordpress(Config::KEY_PERMALINK, '/%postname%/');

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

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    private function getEnvVariableByKey(string $key, mixed $default = null): mixed
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
    private function rotateDotEnvWpSaltVariables(string $dot_env_content): string
    {
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

        return preg_replace(
            array_map(function ($env_variable_name) {
                return "/^($env_variable_name=).*$/m";
            }, $parse_wp_salt_response_regex_result[1] ?? []),
            array_map(function ($salt_value) {
                return '$1' . Str::replace("\"$salt_value\"", '$', 'S');
            }, $parse_wp_salt_response_regex_result[2] ?? []),
            $dot_env_content
        );
    }

    /**
     * @return $this
     * @throws CommandNotFoundException
     * @throws DotEnvNotSetException
     * @throws ExceptionInterface
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function installWpDatabaseCore(): static
    {
        try {
            $this->runWpCliCommand('core is-installed');
            $this->writelnInfoWhenVerbose('WordPress Core Database already installed, skipping.');
            $this->refreshWordlessUserPassword();
        } catch (WpCliCommandReturnedNonZero) {
            $app_name = $this->getEnvVariableByKey('APP_NAME', 'Wordless App');
            $app_url = $this->getEnvVariableByKey('APP_URL', Environment::isFramework() ? '' : null);
            $app_url_with_final_slash = Str::finishWith($app_url, '/');

            $this->runWpCliCommand(
                "core install --url=$app_url_with_final_slash --locale={$this->getWpLanguages()[0]} --title=\"$app_name\" --skip-email --admin_email={$this->firstAdminEmail()} --admin_user="
                . self::FIRST_ADMIN_USERNAME
                . ' --admin_password='
                . self::FIRST_ADMIN_PASSWORD
            );
        }

        return $this;
    }

    /**
     * @return $this
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    private function loadWpLanguages(): static
    {
        $this->wp_languages = Config::wordpressLanguages()->get();

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

        $this->callConsoleCommand(WordlessLanguages::COMMAND_NAME);

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
     * @throws CommandNotFoundException
     * @throws DotEnvNotSetException
     * @throws ExceptionInterface
     * @throws FormatException
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
     * @throws CommandNotFoundException
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws ExceptionInterface
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @throws WpCliCommandReturnedNonZero
     */
    private function performUrlDatabaseFix(): static
    {
        $app_url = $this->getEnvVariableByKey('APP_URL');
        $db_table_prefix = $this->getEnvVariableByKey('DB_TABLE_PREFIX');
        $siteurl_option_value = "$app_url/" . AdminCustomUrlProvider::getCustomUri(false);
        $home_option_value = Environment::isFramework() ? '/' : $app_url;

        /*
         * Raw queries must be used instead of
         * $this->runWpCliCommandSilentlyWithoutInterruption("option update siteurl $siteurl_option_value");
         * and
         * $this->runWpCliCommandSilentlyWithoutInterruption("option update home $home_option_value");
         * because WP-CLI let those options be retrieved from WP_SITEURL and WP_HOME constants when checking if it
         * should change them in database leading to a wrong results of
         * "Success: Value passed for 'siteurl' option is unchanged."
         * and
         * "Success: Value passed for 'home' option is unchanged."
         *
         * Also, for some reason, even with the constants defined, WordPress does not work correctly if the database
         * isn't correct either. (even saying those options won't be retrieved from database)
         * We could test it when running multiple environments and needed to access a URL with a defined custom port
         * like https://another-project.test:4430, which would redirect us to the other project url like
         * https://project.test even with all the right configurations.
         */
        $this->runWpCliCommandWithoutInterruption(
            "db query 'UPDATE {$db_table_prefix}options SET option_value=\"$siteurl_option_value\" WHERE option_name=\"siteurl\"'"
        );
        $this->runWpCliCommandWithoutInterruption(
            "db query 'UPDATE {$db_table_prefix}options SET option_value=\"$home_option_value\" WHERE option_name=\"home\"'"
        );
        $this->runWpCliCommand('db optimize');

        return $this;
    }

    /**
     * @return void
     * @throws CommandNotFoundException
     * @throws DotEnvNotSetException
     * @throws ExceptionInterface
     * @throws FormatException
     * @throws InvalidArgumentException
     */
    private function refreshWordlessUserPassword(): void
    {
        try {
            $app_host = $this->getEnvVariableByKey('APP_HOST', $app_host = 'wordless.wordless');
        } catch (FormatException|DotEnvNotSetException) {
        }

        $email = WordlessUser::USERNAME . "@$app_host";

        $this->runWpCliCommandWithoutInterruption(
            "db query 'UPDATE {$this->getEnvVariableByKey('DB_TABLE_PREFIX')}users SET user_pass=\""
            . WordlessUser::password()
            . "\" WHERE user_email=\"$email\"'"
        );
    }

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    private function registerSchedules(): static
    {
        $this->callConsoleCommand(RegisterSchedules::COMMAND_NAME);

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
     */
    private function resolveForceMode(): static
    {
        if ($this->isForceMode()) {
            $this->deleteWordlessMuPluginForForceMode()
                ->deleteWpConfigForForceMode()
                ->deleteRobotsTxtForForceMode();
        }

        return $this;
    }

    /**
     * @return $this
     * @throws DotEnvNotSetException
     * @throws FailedToChangePathPermissions
     * @throws FormatException
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

    private function writePathNotFoundMessageForForceMode(PathNotFoundException $exception): void
    {
        $this->writelnCommentWhenVerbose("{$exception->getMessage()} Skipped from force mode.");
    }
}
