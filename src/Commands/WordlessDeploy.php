<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\Command\RunWpCliCommand;
use Wordless\Contracts\Command\WriteRobotsTxt;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Helpers\Environment;
use Wordless\Helpers\ProjectPath;

class WordlessDeploy extends WordlessCommand
{
    use RunWpCliCommand, WriteRobotsTxt;

    protected static $defaultName = 'wordless:deploy';

    protected const ALLOW_ROOT_MODE = 'allow-root';

    private array $wp_languages;
    private bool $maintenance_mode;

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Deploys a project.';
    }

    protected function help(): string
    {
        return 'Deploy a project with all commands needed to update it after developing new features.';
    }

    protected function options(): array
    {
        return [
            $this->mountAllowRootModeOption(),
        ];
    }

    /**
     * @return int
     * @throws ExceptionInterface
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     * @throws WpCliCommandReturnedNonZero
     */
    protected function runIt(): int
    {
        $this->loadWpLanguages();

        $this->overwriteWpConfigFromStub();
        $this->overwriteRobotsTxtFromStub();
        $this->performCoreVersionUpdate();
        $this->switchingMaintenanceMode(true);

        try {
            $this->flushWpRewriteRules();
            $this->changeWpTheme();
            $this->activateWpPlugins();
            $this->installWpLanguages();
            $this->makeWpBlogPublic();
            $this->runWpCliCommand('core update-db', true);
        } finally {
            $this->switchingMaintenanceMode(false);
        }

        $this->resolveWpConfigChmod();
        $this->executeWordlessCommand(Migrate::COMMAND_NAME, [], $this->output);
        $this->executeWordlessCommand(SyncRoles::COMMAND_NAME, [], $this->output);

        $this->improveWordless();

        return Command::SUCCESS;
    }

    protected function setup(InputInterface $input, OutputInterface $output)
    {
        parent::setup($input, $output);

        $this->maintenance_mode = false;
    }

    /**
     * @return void
     * @throws WpCliCommandReturnedNonZero
     * @throws ExceptionInterface
     */
    private function activateWpPlugins()
    {
        $this->runWpCliCommand('plugin activate --all');
    }

    /**
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function changeWpTheme()
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
    private function flushWpRewriteRules()
    {
        $permalink_structure = $this->getEnvVariableByKey('WP_PERMALINK', '/%postname%/');
        $this->runWpCliCommand("rewrite structure '$permalink_structure' --hard");
        $this->runWpCliCommand('rewrite flush --hard');
    }

    private function getEnvVariableByKey(string $key, $default = null)
    {
        return Environment::get($key, $default);
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
     * @return void
     * @throws ExceptionInterface
     */
    private function improveWordless()
    {
        $this->executeWordlessCommand(GenerateMustUsePluginsLoader::COMMAND_NAME, [], $this->output);
        $this->executeWordlessCommand(CreateInternalCache::COMMAND_NAME, [], $this->output);
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
            $this->writelnWhenVerbose("WordPress Core Language $language already installed, updating.");

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
            $this->output->writeln(
                'Environment variable WP_LANGUAGES has no value. Skipping language install.'
            );
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

    /**
     * @throws PathNotFoundException
     */
    private function overwriteRobotsTxtFromStub()
    {
        $filename = 'robots.txt';
        $new_robots_txt_filepath = ProjectPath::public($filename);

        $this->mountRobotsTxtFromStub($filename, $new_robots_txt_filepath);
    }

    /**
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     */
    private function overwriteWpConfigFromStub()
    {
        $filename = 'wp-config.php';
        $new_wp_config_filepath = ProjectPath::wpCore($filename);

        if (!copy($wp_config_stub_filepath = ProjectPath::stubs($filename), $new_wp_config_filepath)) {
            throw new FailedToCopyStub($wp_config_stub_filepath, $new_wp_config_filepath);
        }
    }

    /**
     * @return void
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function performCoreVersionUpdate()
    {
        $defined_version_in_env = $this->getEnvVariableByKey('WP_VERSION');

        if (trim($this->runAndGetWpCliCommandOutput('core version')) === $defined_version_in_env) {
            $this->output->writeln("WordPress core is already at version $defined_version_in_env.");
            return;
        }

        $this->runWpCliCommand("core update --force --version=$defined_version_in_env");
        $this->runWpCliCommand('core update-db');
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
            $this->output->writeln("Maintenance mode already {$switch_string}d. Skipping...");
        }

        $this->runWpCliCommand("maintenance-mode $switch_string");

        $this->maintenance_mode = $switch;
    }
}
