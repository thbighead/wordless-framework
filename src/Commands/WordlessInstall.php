<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Wordless\Abstractions\StubMounters\RobotsTxtStubMounter;
use Wordless\Adapters\ConsoleCommand;
use Wordless\Contracts\Command\ForceMode;
use Wordless\Contracts\Command\RunWpCliCommand;
use Wordless\Exceptions\FailedToCopyDotEnvExampleIntoNewDotEnv;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\FailedToDeletePath;
use Wordless\Exceptions\FailedToRewriteDotEnvFile;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Helpers\Config;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\Environment;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;
use Wordless\Hookers\CustomLoginUrl\CustomLoginUrlHooker;

class WordlessInstall extends ConsoleCommand
{
    use ForceMode, RunWpCliCommand;

    protected static $defaultName = 'wordless:install';

    public const TEMP_MAIL = 'temp@mail.not.real';
    protected const ALLOW_ROOT_MODE = 'allow-root';
    protected const FORCE_MODE = 'force';
    private const NO_ASK_MODE = 'no-ask';
    private const NO_DB_CREATION_MODE = 'no-db-creation';
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
    private QuestionHelper $questionHelper;
    private array $wp_languages;
    private bool $maintenance_mode;

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
     * @throws FailedToCopyDotEnvExampleIntoNewDotEnv
     * @throws FailedToCopyStub
     * @throws FailedToDeletePath
     * @throws FailedToRewriteDotEnvFile
     * @throws PathNotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    protected function runIt(): int
    {
        $this->resolveForceMode();

        $this->resolveDotEnv();
        $this->loadWpLanguages();

        $this->downloadWpCore();
        $this->deleteWpContentInsideWpCore();
        $this->createWpConfigFromStub();
        $this->createRobotsTxtFromStub();
        $this->createWpDatabase();
        $this->installWpCore(); // calls switchingMaintenanceMode(false)

        try {
            $this->flushWpRewriteRules();
            $this->activateWpTheme();
            $this->activateWpPlugins();
            $this->installWpLanguages();
            $this->makeWpBlogPublic();
            $this->runWpCliCommand('core update-db', true);
        } finally {
            $this->switchingMaintenanceMode(false);
        }

        $this->resolveWpConfigChmod();
        $this->executeWordlessCommand(
            GeneratePublicWordpressSymbolicLinks::COMMAND_NAME,
            [],
            $this->output
        );
        $this->executeWordlessCommand(Migrate::COMMAND_NAME, [], $this->output);
        $this->executeWordlessCommand(SyncRoles::COMMAND_NAME, [], $this->output);

        if (Environment::isNotLocal()) {
            $this->executeWordlessCommand(CreateInternalCache::COMMAND_NAME, [], $this->output);
        }

        return Command::SUCCESS;
    }

    protected function help(): string
    {
        return 'Completely installs this project calling WP-CLI.';
    }

    protected function options(): array
    {
        return [
            $this->mountAllowRootModeOption(),
            $this->mountForceModeOption('Forces a project installation.'),
            [
                self::OPTION_NAME_FIELD => self::NO_ASK_MODE,
                self::OPTION_MODE_FIELD => InputOption::VALUE_NONE,
                self::OPTION_DESCRIPTION_FIELD => 'Don\'t ask for any input while running.',
            ],
            [
                self::OPTION_NAME_FIELD => self::NO_DB_CREATION_MODE,
                self::OPTION_MODE_FIELD => InputOption::VALUE_NONE,
                self::OPTION_DESCRIPTION_FIELD => 'Don\'t run WP CLI to check and create a database for application.',
            ],
        ];
    }

    protected function setup(InputInterface $input, OutputInterface $output)
    {
        parent::setup($input, $output);

        $this->questionHelper = $this->getHelper('question');
        $this->maintenance_mode = false;
    }

    /**
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function activateWpTheme()
    {
        $this->runWpCliCommand(
            "theme activate {$this->getEnvVariableByKey('WP_THEME', 'wordless')}"
        );
    }

    /**
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function activateWpPlugins()
    {
        $this->runWpCliCommand('plugin activate --all');
    }

    private function ask(string $question, $default = null)
    {
        return $this->questionHelper->ask($this->input, $this->output, new Question($question, $default));
    }

    /**
     * @throws PathNotFoundException|FailedToCopyStub
     */
    private function createRobotsTxtFromStub()
    {
        $new_robots_txt_filepath = ProjectPath::public() . "/" . RobotsTxtStubMounter::STUB_FINAL_FILENAME;

        $robotStubMounter = (new RobotsTxtStubMounter($new_robots_txt_filepath));

        $custom_login_url = Config::tryToGetOrDefault('admin.' . CustomLoginUrlHooker::WP_CUSTOM_LOGIN_URL, false);
        $robotStubMounter->setReplaceContentDictionary(
            [
                '{APP_URL}' => Str::finishWith($this->getEnvVariableByKey('APP_URL', ''), '/'),
                '#custom_login_url' => $custom_login_url ? "Disallow: /$custom_login_url/" : ''
            ]
        )
            ->mountNewFile();
    }

    /**
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     */
    private function createWpConfigFromStub()
    {
        $filename = 'wp-config.php';
        $new_wp_config_filepath = ProjectPath::wpCore() . "/$filename";

        if ((($supposed_already_existing_wp_config_filepath = realpath($new_wp_config_filepath)) !== false)
            && str_contains(file_get_contents($new_wp_config_filepath), '@author Wordless')) {
            $this->writelnCommentWhenVerbose(
                "A config file at $supposed_already_existing_wp_config_filepath already exists, skipping."
            );

            return;
        }

        if (!copy($wp_config_stub_filepath = ProjectPath::stubs($filename), $new_wp_config_filepath)) {
            throw new FailedToCopyStub($wp_config_stub_filepath, $new_wp_config_filepath);
        }
    }

    /**
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function createWpDatabase()
    {
        if ($this->input->getOption(self::NO_DB_CREATION_MODE)) {
            $this->writelnInfoWhenVerbose(
                'Running with no database creation mode. Skipping database check and creation.'
            );

            return;
        }

        $database_username = $this->getEnvVariableByKey('DB_USER');
        $database_password = $this->getEnvVariableByKey('DB_PASSWORD');

        if ($this->runWpCliCommand(
                "db check --dbuser=$database_username --dbpass=$database_password",
                true
            ) == 0) {
            $this->writelnCommentWhenVerbose('WordPress Database already created, skipping.');

            return;
        }

        $this->runWpCliCommand("db create --dbuser=$database_username --dbpass=$database_password");
    }

    /**
     * @return void
     * @throws FailedToDeletePath
     */
    private function deleteWpContentInsideWpCore()
    {
        $this->writeln('Check wp/wp-core/wp-content');

        try {
            DirectoryFiles::recursiveDelete(ProjectPath::wpCore('wp-content'));
            $this->writeSuccess('Success: ');
            $this->writeln('"wp/wp-core/wp-content" directory deleted!');
        } catch (PathNotFoundException $exception) {
            $this->writeInfo('Success: ');
            $this->writeln(
                '"wp/wp-core/wp-content" directory not created on Wordpress install, skipping delete...'
            );
        }
    }

    /**
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function downloadWpCore()
    {
        if ($this->runWpCliCommand('core version --extra', true) == 0) {
            $this->writelnCommentWhenVerbose('WordPress Core already downloaded, skipping.');

            return;
        }

        $wp_version = $this->getEnvVariableByKey('WP_VERSION', 'latest');

        $this->runWpCliCommand("core download --version=$wp_version --skip-content");
    }

    /**
     * @param string $dot_env_filepath
     * @throws ClientExceptionInterface
     * @throws FailedToRewriteDotEnvFile
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function fillDotEnv(string $dot_env_filepath)
    {
        $dot_env_content = $this->guessAndResolveDotEnvWpSaltVariables(
            $dot_env_original_content = file_get_contents($dot_env_filepath)
        );

        if ($dot_env_original_content !== $dot_env_content) {
            if (file_put_contents($dot_env_filepath, $dot_env_content) === false) {
                throw new FailedToRewriteDotEnvFile($dot_env_filepath, $dot_env_content);
            }
        }

        if (empty($not_filled_variables = $this->getDotEnvNotFilledVariables($dot_env_content))) {
            return;
        }

        $this->writeln("We'll need to fill up $dot_env_filepath: ('null' values to comment line)");

        $filler_dictionary = $this->mountDotEnvFillerDictionary($not_filled_variables);
        $dot_env_content = str_replace(
            array_keys($filler_dictionary),
            array_values($filler_dictionary),
            $dot_env_content
        );

        if (file_put_contents($dot_env_filepath, $dot_env_content) === false) {
            throw new FailedToRewriteDotEnvFile($dot_env_filepath, $dot_env_content);
        }

        // populates an internal array with env variables freshly new.
        $this->fresh_new_env_content = (new Dotenv)->parse($dot_env_content);
    }

    /**
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function flushWpRewriteRules()
    {
        $permalink_structure = $this->getEnvVariableByKey('WP_PERMALINK', '/%postname%/');
        $this->runWpCliCommand("rewrite structure '$permalink_structure' --hard");
        $this->runWpCliCommand('rewrite flush --hard');
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

    private function loadWpLanguages(): void
    {
        $this->wp_languages = explode(',', $this->getEnvVariableByKey('WP_LANGUAGES', ''));
    }

    /**
     * @return string
     * @throws FailedToCopyDotEnvExampleIntoNewDotEnv
     * @throws PathNotFoundException
     */
    private function getOrCreateDotEnvFilepath(): string
    {
        if (!DOT_ENV_NOT_LOADED) {
            return ProjectPath::root('.env');
        }

        return ProjectPath::realpath(Environment::createDotEnvFromExample());
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
                return Str::replace("\"$salt_value\"", '$', 'S');
            }, $parse_wp_salt_response_regex_result[2] ?? []),
            $dot_env_content
        );
    }

    private function guessOrAskDotEnvVariableValue(string $variable_marked_as_not_filled)
    {
        $variable_default = $_ENV[$variable_marked_as_not_filled] ?? '';

        if ($this->input->getOption(self::NO_ASK_MODE)) {
            return $variable_default;
        }

        $variable_name = substr($variable_marked_as_not_filled, 1); // removing '$'
        $variable_default = $_ENV[$variable_marked_as_not_filled] ??
            Environment::COMMONLY_DOT_ENV_DEFAULT_VALUES[$variable_name] ??
            '';

        return $this->ask(
            "What should be $variable_name value? [$variable_default] ",
            $variable_default
        );
    }

    /**
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function installWpCore()
    {
        if ($this->runWpCliCommand('core is-installed', true) == 0) {
            $this->writelnInfoWhenVerbose('WordPress Core already installed, minor updating.');

            $this->switchingMaintenanceMode(true);

            if ($this->getEnvVariableByKey('WP_VERSION', 'latest') === 'latest') {
                $this->performMinorUpdate();
            }

            return;
        }

        $app_url = $this->getEnvVariableByKey('APP_URL');
        $app_url_with_final_slash = Str::finishWith($app_url, '/');
        $app_name = $this->getEnvVariableByKey('APP_NAME', 'Wordless App');

        $this->runWpCliCommand(
            "core install --url=$app_url_with_final_slash --title=\"$app_name\" --skip-email --admin_user=temp --admin_email="
            . self::TEMP_MAIL
        );

        $this->switchingMaintenanceMode(true);

        $this->performUrlDatabaseFix($app_url, $app_url_with_final_slash);
    }

    /**
     * @param string $language
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function installWpCoreLanguage(string $language)
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
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function installWpLanguages()
    {
        if (empty($wp_languages = $this->getWpLanguages())) {
            $this->writelnWarning('Environment variable WP_LANGUAGES has no value. Skipping language install.');

            return;
        }

        $this->installWpCoreLanguage($wp_languages[0]);

        foreach ($wp_languages as $language) {
            $this->installWpPluginsLanguage($language);
        }
    }

    /**
     * @param string $language
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function installWpPluginsLanguage(string $language)
    {
        $this->runWpCliCommand("language plugin install $language --all", true);
        $this->runWpCliCommand("language plugin update $language --all", true);
    }

    /**
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function makeWpBlogPublic()
    {
        $blog_public = $this->getEnvVariableByKey('APP_ENV') === Environment::PRODUCTION ? '1' : '0';

        $this->runWpCliCommand("option update blog_public $blog_public");
    }

    private function mountDotEnvFillerDictionary(array $not_filled_variables): array
    {
        $filler_dictionary = [];

        foreach ($not_filled_variables as $variable) {
            $variable_value = $this->guessOrAskDotEnvVariableValue($variable);

            if ($variable_value === 'null') {
                $variable_name = substr($variable, 1); // removing '$'
                $filler_dictionary["$variable_name=$variable"] = Environment::DOT_ENV_COMMENT_MARK . "$variable_name=";
                continue;
            }

            $filler_dictionary[$variable] = "\"$variable_value\"";
        }

        return $filler_dictionary;
    }

    /**
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function performMinorUpdate()
    {
        try {
            $this->runWpCliCommand('core update --minor');
        } finally {
            $this->switchingMaintenanceMode(false);
        }
    }

    /**
     * @param string $app_url
     * @param string $app_url_with_final_slash
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function performUrlDatabaseFix(string $app_url, string $app_url_with_final_slash)
    {
        try {
            $this->runWpCliCommand("option update siteurl {$app_url_with_final_slash}wp-core/");
            $this->runWpCliCommand("option update home $app_url");
        } finally {
            $this->switchingMaintenanceMode(false);
        }
    }

    /**
     * @throws ClientExceptionInterface
     * @throws FailedToCopyDotEnvExampleIntoNewDotEnv
     * @throws FailedToRewriteDotEnvFile
     * @throws PathNotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function resolveDotEnv()
    {
        $this->fillDotEnv($this->getOrCreateDotEnvFilepath());
    }

    /**
     * @throws FailedToDeletePath
     */
    private function resolveForceMode()
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
    }

    /**
     * @throws PathNotFoundException
     */
    private function resolveWpConfigChmod()
    {
        if ($this->getEnvVariableByKey('APP_ENV') === Environment::PRODUCTION) {
            chmod(ProjectPath::wpCore('wp-config.php'), 0660);
        }
    }

    /**
     * @param bool $switch
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function switchingMaintenanceMode(bool $switch)
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
