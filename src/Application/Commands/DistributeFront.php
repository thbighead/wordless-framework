<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class DistributeFront extends ConsoleCommand
{
    final public const COMMAND_NAME = 'distribute';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return '';
    }


    protected function help(): string
    {
        return '';
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

