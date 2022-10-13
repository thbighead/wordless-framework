<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Contracts\Command\ProjectAdditionalCommands;
use Wordless\Helpers\Environment;

class ProjectInstall extends WordlessInstall
{
    use ProjectAdditionalCommands;

    protected static $defaultName = 'project:install';

    protected function description(): string
    {
        return 'Installs this specific project.';
    }

    protected function help(): string
    {
        return 'Install this project with all commands needed to update it after developing new features. This is a custom command created specifically for this project.';
    }

    protected function runIt(): int
    {
        $this->wrapScriptWithMessages("Initializing install...\n", function () {
            parent::runIt();

            if (Environment::get('WP_THEME') === 'infobase-wp-theme') {
                $this->runNpmCommands();
            }
            $this->synchronizeAcfs();
        });

        return Command::SUCCESS;
    }
}
