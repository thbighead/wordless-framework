<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class Diagnostics extends ConsoleCommand
{
    final public const COMMAND_NAME = 'diagnostics';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'A complete overview of your project setup. Useful to check environment options installed.';
    }


    protected function help(): string
    {
        return 'Creates a full report with PHP info (CLI and FPM), development-shareable environment variables and WP CLI profile hook, profile stage, cli info and doctor list commands.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [];
    }

    protected function runIt(): int
    {
        return Command::SUCCESS;
    }
}

