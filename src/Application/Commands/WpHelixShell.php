<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class WpHelixShell extends ConsoleCommand
{
    use LoadWpConfig;
    use RunWpCliCommand;

    final public const COMMAND_NAME = 'wp:helix';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Initializes an interactive shell.';
    }


    protected function help(): string
    {
        return "As many interactive shells have a name, we call ours as Helix (based on the Helix Fossil, since it's a shell).\n\"ALL HAIL HELIX FOSSIL!\"\n\"ALL HAIL LORD HELIX!\"\n - Twitch plays Pokémon chat.";
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    protected function runIt(): int
    {
        $this->callWpCliCommandSilentlyWithoutInterruption('shell');

        return Command::SUCCESS;
    }
}
