<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits;

use Symfony\Component\Console\Command\Command;

trait ConsoleCommandInstantiator
{
    private array $consoleCommandInstancesCache = [];

    private function getConsoleCommandInstance(string $command_name): Command
    {
        $commandObject = $this->consoleCommandInstancesCache[$command_name] ?? null;

        if (!($commandObject instanceof Command)) {
            $commandObject = $this->consoleCommandInstancesCache[$command_name] =
                $this->getApplication()->find($command_name);
        }

        return $commandObject;
    }
}
