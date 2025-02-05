<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Wordless\Infrastructure\ConsoleCommand;

trait ConsoleCommandInstantiator
{
    /** @var array<string, ConsoleCommand> $consoleCommandInstancesCache */
    private array $consoleCommandInstancesCache = [];

    /**
     * @param string $command_name
     * @return ConsoleCommand
     * @throws CommandNotFoundException
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function getConsoleCommandInstance(string $command_name): ConsoleCommand
    {
        $commandObject = $this->consoleCommandInstancesCache[$command_name] ?? null;

        if (!($commandObject instanceof ConsoleCommand)) {
            $commandObject = $this->consoleCommandInstancesCache[$command_name] =
                $this->getApplication()->find($command_name);
        }

        return $commandObject;
    }
}
