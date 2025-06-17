<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToRunWpCliCommand;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class HelixShell extends ConsoleCommand
{
    use LoadWpConfig;
    use RunWpCliCommand;

    final public const COMMAND_NAME = 'helix';

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
        return [
            ...$this->mountRunWpCliOptions(),
        ];
    }

    /**
     * @return int
     * @throws FailedToRunWpCliCommand
     */
    protected function runIt(): int
    {
        $this->runWpCliCommandWithoutInterruption('shell');

        return Command::SUCCESS;
    }
}
