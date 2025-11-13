<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Random\RandomException;
use Symfony\Component\Console\Command\Command;
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
use Wordless\Application\Commands\Exceptions\FailedToRunCommand;
use Wordless\Application\Commands\Migrations\Migrate;
use Wordless\Application\Commands\Schedules\RegisterSchedules;
use Wordless\Application\Commands\Traits\ForceMode;
use Wordless\Application\Commands\Traits\NoTtyMode\DTO\NoTtyModeOptionDTO;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToRunWpCliCommand;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToActivateWpPluginsException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToActivateWpThemeException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToApplyAdminConfigurationException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToCreateCacheException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToCreateConfigFromStubsException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToCreateRobotsTxtException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToCreateWordlessPluginFromStubException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToCreateWpDatabaseException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToDeleteFileForForceModeException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToFillDotEnvException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToFixUrlOnDatabaseException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToFlushCacheException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToFlushWpRewriteRulesException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToGenerateSymbolicLinksException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToGetEnvVariableException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToInstallWpCoreLanguagesException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToInstallWpDatabaseCoreException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToInstallWpLanguagesException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToInstallWpPluginsLanguageException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToLoadWpLanguagesException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToMakeBlogPublicException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToRefreshWordlessUserPassword;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToRegisterSchedulesException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToResolveDotEnvException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToResolveForceModeException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToResolveWpConfigChmodException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToRotateDotEnvWpSaltVariablesException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToRunCoreStepsException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToRunMigrationsException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToSwitchToMaintenanceModeException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToSyncRolesException;
use Wordless\Application\Commands\WordlessInstall\Exceptions\FailedToUpdateDatabaseException;
use Wordless\Application\Commands\WordlessInstall\Traits\ForFramework;
use Wordless\Application\Commands\WordlessInstall\Traits\ForFramework\Exceptions\FailedToGenerateEmptyWordlessThemeException;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Traits\Internal\Exceptions\FailedToLoadConfigFile;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToChangePathPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToRewriteDotEnvFile;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\RobotsTxtStubMounter;
use Wordless\Application\Mounters\Stub\WordlessPluginStubMounter;
use Wordless\Application\Providers\AdminCustomUrlProvider;
use Wordless\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\Internal\Exceptions\CallInternalCommandException;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Create\Exceptions\FailedToCreateUser;
use Wordless\Wordpress\Models\User\WordlessUser;

class WordlessInstall extends ConsoleCommand
{
    use ForceMode;
    use ForFramework;
    use RunWpCliCommand;

    public const COMMAND_NAME = 'wordless:install';
    final public const WORDLESS_ADMIN_USER = 'wordless';
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
     * @throws FailedToSwitchToMaintenanceModeException
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
     * @throws FailedToRunCommand
     */
    protected function runIt(): int
    {
        try {
            $this->resolveForceMode()
                ->flushCache()
                ->resolveDotEnv()
                ->loadWpLanguages()
                ->createWpConfigFromStub()
                ->createRobotsTxtFromStub()
                ->createWordlessPluginFromStub()
                ->createWpDatabase()
                ->coreSteps()
                ->createWordlessUser()
                ->createCache()
                ->registerSchedules()
                ->runMigrations()
                ->syncRoles()
                ->resolveWpConfigChmod();

            return Command::SUCCESS;
        } catch (FailedToCreateCacheException
        |FailedToCreateConfigFromStubsException
        |FailedToCreateRobotsTxtException
        |FailedToCreateUser
        |FailedToCreateWordlessPluginFromStubException
        |FailedToCreateWpDatabaseException
        |FailedToFlushCacheException
        |FailedToLoadWpLanguagesException
        |FailedToRefreshWordlessUserPassword
        |FailedToRegisterSchedulesException
        |FailedToResolveDotEnvException
        |FailedToResolveForceModeException
        |FailedToResolveWpConfigChmodException
        |FailedToRunCoreStepsException
        |FailedToRunMigrationsException
        |FailedToSyncRolesException
        |RandomException $exception) {
            throw new FailedToRunCommand(static::COMMAND_NAME, $exception);
        }
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
     * @throws FailedToActivateWpThemeException
     */
    private function activateWpTheme(): static
    {
        try {
            if (Environment::isFramework()) {
                $this->generateEmptyWordlessTheme();
            }

            $this->runWpCliCommand(
                'theme activate ' . Config::wordpress(Config::KEY_THEME, 'wordless')
            );

            return $this;
        } catch (FailedToRunWpCliCommand|FailedToGenerateEmptyWordlessThemeException|WpCliCommandReturnedNonZero $exception) {
            throw new FailedToActivateWpThemeException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToActivateWpPluginsException
     */
    private function activateWpPlugins(): static
    {
        try {
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
        } catch (EmptyConfigKey|FailedToLoadConfigFile|FailedToRunWpCliCommand|WpCliCommandReturnedNonZero $exception) {
            throw new FailedToActivateWpPluginsException($exception);
        }
    }

    /**
     * @return void
     * @throws FailedToApplyAdminConfigurationException
     */
    private function applyAdminConfiguration(): void
    {
        try {
            $this->callConsoleCommand(ConfigureDateOptions::COMMAND_NAME, [
                '--' . NoTtyModeOptionDTO::NO_TTY_MODE => $this->isNoTtyMode(),
            ]);
        } catch (CallInternalCommandException|CliReturnedNonZero $exception) {
            throw new FailedToApplyAdminConfigurationException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToCreateWordlessPluginFromStubException
     */
    private function createWordlessPluginFromStub(): static
    {
        try {
            WordlessPluginStubMounter::make(ProjectPath::wpMustUsePlugins() . '/' . self::MU_PLUGIN_FILE_NAME)
                ->mountNewFile();

            return $this;
        } catch (FailedToCopyStub|PathNotFoundException $exception) {
            throw new FailedToCreateWordlessPluginFromStubException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToRunCoreStepsException
     */
    private function coreSteps(): static
    {
        try {
            $this->switchingMaintenanceMode(true);

            $this->performUrlDatabaseFix()
                ->flushWpRewriteRules()
                ->activateWpTheme()
                ->activateWpPlugins()
                ->installWpLanguages()
                ->makeWpBlogPublic()
                ->databaseUpdate()
                ->generateSymbolicLinks()
                ->applyAdminConfiguration();
        } catch (FailedToSwitchToMaintenanceModeException|FailedToActivateWpPluginsException|FailedToActivateWpThemeException|FailedToApplyAdminConfigurationException|FailedToFixUrlOnDatabaseException|FailedToFlushWpRewriteRulesException|FailedToGenerateSymbolicLinksException|FailedToInstallWpLanguagesException|FailedToMakeBlogPublicException|FailedToUpdateDatabaseException $exception) {
            throw new FailedToRunCoreStepsException($exception);
        } finally {
            try {
                $this->switchingMaintenanceMode(false);
                require_once ProjectPath::wpCore('wp-config.php');
            } catch (FailedToSwitchToMaintenanceModeException|PathNotFoundException $exception) {
                throw new FailedToRunCoreStepsException($exception);
            }
        }

        return $this;
    }

    /**
     * @return $this
     * @throws FailedToCreateCacheException
     */
    private function createCache(): static
    {
        try {
            if ($this->getEnvVariableByKey('APP_ENV') !== Environment::LOCAL) {
                $this->callConsoleCommand(CreateInternalCache::COMMAND_NAME);
            }

            return $this;
        } catch (CallInternalCommandException|CliReturnedNonZero|FailedToGetEnvVariableException $exception) {
            throw new FailedToCreateCacheException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToCreateRobotsTxtException
     */
    private function createRobotsTxtFromStub(): static
    {
        try {
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
        } catch (FailedToCopyStub|FailedToGetEnvVariableException|PathNotFoundException $exception) {
            throw new FailedToCreateRobotsTxtException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToCreateUser
     * @throws RandomException
     */
    private function createWordlessUser(): static
    {
        WordlessUser::create();

        return $this;
    }

    /**
     * @return $this
     * @throws FailedToCreateConfigFromStubsException
     */
    private function createWpConfigFromStub(): static
    {
        try {
            $this->callConsoleCommand(PublishWpConfigPhp::COMMAND_NAME);

            return $this;
        } catch (CallInternalCommandException|CliReturnedNonZero $exception) {
            throw new FailedToCreateConfigFromStubsException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToCreateWpDatabaseException
     * @throws FailedToRefreshWordlessUserPassword
     */
    private function createWpDatabase(): static
    {
        try {
            $this->runWpCliCommand('db check');
            $this->writelnCommentWhenVerbose('WordPress Database already created, skipping.');

            return $this->installWpDatabaseCore();
        } catch (WpCliCommandReturnedNonZero) {
            try {
                $this->runWpCliCommand('db create');

                return $this->installWpDatabaseCore();
            } catch (WpCliCommandReturnedNonZero|FailedToRunWpCliCommand|FailedToInstallWpDatabaseCoreException $exception) {
                throw new FailedToCreateWpDatabaseException($exception);
            }
        } catch (FailedToRunWpCliCommand|FailedToInstallWpDatabaseCoreException $exception) {
            throw new FailedToCreateWpDatabaseException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToUpdateDatabaseException
     */
    private function databaseUpdate(): static
    {
        try {
            $this->runWpCliCommand('core update-db');

            return $this;
        } catch (FailedToRunWpCliCommand|WpCliCommandReturnedNonZero $exception) {
            throw new FailedToUpdateDatabaseException($exception);
        }
    }

    /**
     * @param string $filepath
     * @return void
     * @throws FailedToDeleteFileForForceModeException
     */
    private function deleteFileForForceMode(string $filepath): void
    {
        try {
            $this->wrapScriptWithMessages(
                "Deleting $filepath...",
                function () use ($filepath) {
                    DirectoryFiles::delete($filepath);
                }
            );
        } catch (FailedToDeletePath|PathNotFoundException $exception) {
            throw new FailedToDeleteFileForForceModeException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToDeleteFileForForceModeException
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
     * @throws FailedToDeleteFileForForceModeException
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
     * @throws FailedToDeleteFileForForceModeException
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
     * @throws FailedToFillDotEnvException
     */
    private function fillDotEnv(string $dot_env_filepath): void
    {
        try {
            if (($dot_env_content = $this->rotateDotEnvWpSaltVariables(
                    $dot_env_original_content = DirectoryFiles::getFileContent($dot_env_filepath)
                )) !== $dot_env_original_content) {
                Environment::rewriteDotEnvFile($dot_env_filepath, $dot_env_content);
            }

            // populates an internal array with env variables freshly new.
            $this->fresh_new_env_content = (new Dotenv)->parse($dot_env_content);
        } catch (FailedToGetFileContent|FailedToRewriteDotEnvFile|FailedToRotateDotEnvWpSaltVariablesException|FormatException|PathNotFoundException $exception) {
            throw new FailedToFillDotEnvException($exception);
        }
    }

    private function firstAdminEmail(): string
    {
        try {
            $app_host = $this->getEnvVariableByKey('APP_HOST', $app_host = 'wordless.wordless');
        } catch (FailedToGetEnvVariableException) {
        }

        return self::FIRST_ADMIN_USERNAME . "@$app_host";
    }

    /**
     * @return $this
     * @throws FailedToFlushCacheException
     */
    private function flushCache(): static
    {
        try {
            $this->callConsoleCommand(CleanInternalCache::COMMAND_NAME);

            return $this;
        } catch (CallInternalCommandException|CliReturnedNonZero $exception) {
            throw new FailedToFlushCacheException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToFlushWpRewriteRulesException
     */
    private function flushWpRewriteRules(): static
    {
        try {
            $permalink_structure = Config::wordpress(Config::KEY_PERMALINK, '/%postname%/');

            $this->runWpCliCommand("rewrite structure '$permalink_structure' --hard");
            $this->runWpCliCommand('rewrite flush --hard');

            return $this;
        } catch (FailedToRunWpCliCommand|WpCliCommandReturnedNonZero $exception) {
            throw new FailedToFlushWpRewriteRulesException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToGenerateSymbolicLinksException
     */
    private function generateSymbolicLinks(): static
    {
        try {
            if (Environment::isNotFramework()) {
                $this->callConsoleCommand(GeneratePublicWordpressSymbolicLinks::COMMAND_NAME);
            }

            return $this;
        } catch (CallInternalCommandException|CliReturnedNonZero $exception) {
            throw new FailedToGenerateSymbolicLinksException($exception);
        }
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     * @throws FailedToGetEnvVariableException
     */
    private function getEnvVariableByKey(string $key, mixed $default = null): mixed
    {
        try {
            return $this->fresh_new_env_content[$key] ?? Environment::get($key, $default);
        } catch (CannotResolveEnvironmentGet $exception) {
            throw new FailedToGetEnvVariableException($exception);
        }
    }

    private function getWpLanguages(): array
    {
        return $this->wp_languages;
    }

    /**
     * @param string $dot_env_content
     * @return string
     * @throws FailedToRotateDotEnvWpSaltVariablesException
     */
    private function rotateDotEnvWpSaltVariables(string $dot_env_content): string
    {
        try {
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
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $exception) {
            throw new FailedToRotateDotEnvWpSaltVariablesException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToInstallWpDatabaseCoreException
     * @throws FailedToRefreshWordlessUserPassword
     */
    private function installWpDatabaseCore(): static
    {
        try {
            $this->runWpCliCommand('core is-installed');
            $this->writelnInfoWhenVerbose('WordPress Core Database already installed, skipping.');
            $this->refreshWordlessUserPassword();
        } catch (WpCliCommandReturnedNonZero) {
            try {
                $app_name = $this->getEnvVariableByKey('APP_NAME', 'Wordless App');
                $app_url = $this->getEnvVariableByKey('APP_URL', Environment::isFramework() ? '' : null);
                $app_url_with_final_slash = Str::finishWith($app_url, '/');

                $this->runWpCliCommand(
                    "core install --url=$app_url_with_final_slash --locale={$this->getWpLanguages()[0]} --title=\"$app_name\" --skip-email --admin_email={$this->firstAdminEmail()} --admin_user="
                    . self::FIRST_ADMIN_USERNAME
                    . ' --admin_password='
                    . self::FIRST_ADMIN_PASSWORD
                );
            } catch (WpCliCommandReturnedNonZero|FailedToRunWpCliCommand|FailedToGetEnvVariableException $exception) {
                throw new FailedToInstallWpDatabaseCoreException($exception);
            }
        } catch (FailedToRunWpCliCommand $exception) {
            throw new FailedToInstallWpDatabaseCoreException($exception);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws FailedToLoadWpLanguagesException
     */
    private function loadWpLanguages(): static
    {
        try {
            $this->wp_languages = Config::wordpressLanguages()->get();

            if (empty($this->wp_languages)) {
                $this->wp_languages = ['en_US'];
            }

            return $this;
        } catch (FailedToLoadConfigFile|FailedToRetrieveConfigFromWordpressConfigFile $exception) {
            throw new FailedToLoadWpLanguagesException($exception);
        }
    }

    /**
     * @param string $language
     * @return void
     * @throws FailedToInstallWpCoreLanguagesException
     */
    private function installWpCoreLanguage(string $language): void
    {
        try {
            $this->runWpCliCommand("language core is-installed $language");
            $this->writelnInfoWhenVerbose("WordPress Core Language $language already installed, updating.");
        } catch (WpCliCommandReturnedNonZero) {
            try {
                $this->runWpCliCommand("language core install $language --activate");
            } catch (WpCliCommandReturnedNonZero|FailedToRunWpCliCommand $exception) {
                throw new FailedToInstallWpCoreLanguagesException($exception);
            }

            return;
        } catch (FailedToRunWpCliCommand $exception) {
            throw new FailedToInstallWpCoreLanguagesException($exception);
        }

        try {
            $this->runWpCliCommand('language core update');
            $this->runWpCliCommand("site switch-language $language");
        } catch (FailedToRunWpCliCommand|WpCliCommandReturnedNonZero $exception) {
            throw new FailedToInstallWpCoreLanguagesException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToInstallWpLanguagesException
     */
    private function installWpLanguages(): static
    {
        try {
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
        } catch (CallInternalCommandException|CliReturnedNonZero|FailedToInstallWpCoreLanguagesException|FailedToInstallWpPluginsLanguageException $exception) {
            throw new FailedToInstallWpLanguagesException($exception);
        }
    }

    /**
     * @param string $language
     * @return void
     * @throws FailedToInstallWpPluginsLanguageException
     */
    private function installWpPluginsLanguage(string $language): void
    {
        try {
            $this->runWpCliCommand("language plugin install $language --all");
            $this->runWpCliCommand("language plugin update $language --all");
        } catch (WpCliCommandReturnedNonZero|FailedToRunWpCliCommand $exception) {
            throw new FailedToInstallWpPluginsLanguageException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToMakeBlogPublicException
     */
    private function makeWpBlogPublic(): static
    {
        try {
            $blog_public = $this->getEnvVariableByKey('APP_ENV') === Environment::PRODUCTION ? '1' : '0';
            $this->runWpCliCommand("option update blog_public $blog_public");

            return $this;
        } catch (FailedToGetEnvVariableException|WpCliCommandReturnedNonZero|FailedToRunWpCliCommand $exception) {
            throw new FailedToMakeBlogPublicException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToFixUrlOnDatabaseException
     */
    private function performUrlDatabaseFix(): static
    {
        try {
            $app_url = $this->getEnvVariableByKey('APP_URL');
            $db_table_prefix = $this->getEnvVariableByKey('DB_TABLE_PREFIX', 'wp_');
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
        } catch (WpCliCommandReturnedNonZero|FailedToGetEnvVariableException|FailedToRetrieveConfigFromWordpressConfigFile|FailedToRunWpCliCommand $exception) {
            throw new FailedToFixUrlOnDatabaseException($exception);
        }
    }

    /**
     * @return void
     * @throws FailedToRefreshWordlessUserPassword
     */
    private function refreshWordlessUserPassword(): void
    {
        try {
            $app_host = $this->getEnvVariableByKey('APP_HOST', $app_host = 'wordless.wordless');
        } catch (FailedToGetEnvVariableException) {
        }

        $email = self::WORDLESS_ADMIN_USER . "@$app_host";

        try {
            $this->runWpCliCommandWithoutInterruption(
                "db query 'UPDATE {$this->getEnvVariableByKey('DB_TABLE_PREFIX', 'wp_')}users SET user_pass=\""
                . Str::random()
                . "\" WHERE user_email=\"$email\"'"
            );
        } catch (RandomException|FailedToRunWpCliCommand|FailedToGetEnvVariableException $exception) {
            throw new FailedToRefreshWordlessUserPassword($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToRegisterSchedulesException
     */
    private function registerSchedules(): static
    {
        try {
            $this->callConsoleCommand(RegisterSchedules::COMMAND_NAME);
        } catch (CallInternalCommandException|CliReturnedNonZero $exception) {
            throw new FailedToRegisterSchedulesException($exception);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws FailedToResolveDotEnvException
     */
    private function resolveDotEnv(): static
    {
        try {
            $this->fillDotEnv(ProjectPath::root('.env'));

            return $this;
        } catch (FailedToFillDotEnvException|PathNotFoundException $exception) {
            throw new FailedToResolveDotEnvException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToResolveForceModeException
     */
    private function resolveForceMode(): static
    {
        try {
            if ($this->isForceMode()) {
                $this->deleteWordlessMuPluginForForceMode()
                    ->deleteWpConfigForForceMode()
                    ->deleteRobotsTxtForForceMode();
            }

            return $this;
        } catch (FailedToDeleteFileForForceModeException|InvalidArgumentException $exception) {
            throw new FailedToResolveForceModeException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToResolveWpConfigChmodException
     */
    private function resolveWpConfigChmod(): static
    {
        try {
            if ($this->getEnvVariableByKey('APP_ENV') === Environment::PRODUCTION) {
                DirectoryFiles::changePermissions(ProjectPath::wpCore('wp-config.php'), 0660);
            }

            return $this;
        } catch (FailedToChangePathPermissions|FailedToGetEnvVariableException|PathNotFoundException $exception) {
            throw new FailedToResolveWpConfigChmodException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToRunMigrationsException
     */
    private function runMigrations(): static
    {
        try {
            if (Environment::isNotFramework()) {
                $this->callConsoleCommand(Migrate::COMMAND_NAME);
            }

            return $this;
        } catch (CallInternalCommandException|CliReturnedNonZero $exception) {
            throw new FailedToRunMigrationsException($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToSyncRolesException
     */
    private function syncRoles(): static
    {
        try {
            $this->callConsoleCommand(SyncRoles::COMMAND_NAME);
        } catch (CliReturnedNonZero|CallInternalCommandException $exception) {
            throw new FailedToSyncRolesException($exception);
        }

        return $this;
    }

    /**
     * @param bool $switch
     * @return void
     * @throws FailedToSwitchToMaintenanceModeException
     */
    private function switchingMaintenanceMode(bool $switch): void
    {
        try {
            $switch_string = $switch ? 'activate' : 'deactivate';

            if ($this->maintenance_mode === $switch) {
                $this->writelnComment("Maintenance mode already {$switch_string}d. Skipping...");

                return;
            }

            $this->runWpCliCommand("maintenance-mode $switch_string");

            $this->maintenance_mode = $switch;
        } catch (FailedToRunWpCliCommand|WpCliCommandReturnedNonZero $exception) {
            throw new FailedToSwitchToMaintenanceModeException($exception);
        }
    }

    private function writePathNotFoundMessageForForceMode(PathNotFoundException $exception): void
    {
        $this->writelnCommentWhenVerbose("{$exception->getMessage()} Skipped from force mode.");
    }
}
