<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;

trait ConsoleCommandInstantiator
{
    /** @var array<string, Command> $consoleCommandInstancesCache */
    private array $consoleCommandInstancesCache = [];

    /**
     * @param string $command_name
     * @return Command
     * @throws CommandNotFoundException
     */
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
