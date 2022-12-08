<?php

namespace Wordless\Contracts\Command;

use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Commands\ThemeNpmCaller;
use Wordless\Helpers\Environment;

trait ProjectAdditionalCommands
{
    /**
     * @return void
     * @throws ExceptionInterface
     */
    private function runNpmCommands()
    {
        $npm_script = Environment::get('APP_ENV') === Environment::PRODUCTION ? 'prod' : 'dev';

        $this->executeWordlessCommand(ThemeNpmCaller::COMMAND_NAME, [
            ThemeNpmCaller::NPM_FULL_COMMAND_STRING_ARGUMENT_NAME => 'install',
        ], $this->output);
        $this->executeWordlessCommand(ThemeNpmCaller::COMMAND_NAME, [
            ThemeNpmCaller::NPM_FULL_COMMAND_STRING_ARGUMENT_NAME => "run $npm_script",
        ], $this->output);
    }

    /**
     * @return void
     * @throws ExceptionInterface
     */
    private function synchronizeAcfs()
    {
        $this->executeWordlessCommand('acf:clean', [], $this->output);
        $this->executeWordlessCommand('acf:import', [], $this->output);
    }
}
