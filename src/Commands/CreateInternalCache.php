<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Abstractions\InternalCache;
use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\LoadWpConfig;
use Wordless\Exception\FailedToCopyStub;
use Wordless\Exception\PathNotFoundException;

class CreateInternalCache extends WordlessCommand
{
    use LoadWpConfig;

    public const COMMAND_NAME = 'cache:create';
    protected static $defaultName = self::COMMAND_NAME;

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Generate Wordless internal cache files.';
    }

    protected function help(): string
    {
        return 'Generate Wordless internal cache files to avoid some uses of reflections and calculations throughout system booting.';
    }

    protected function options(): array
    {
        return [];
    }

    protected function runIt(): int
    {
        try {
            $this->wrapScriptWithMessages(
                'Generating internal caches...',
                function () {
                    InternalCache::generate();
                }
            );
            return Command::SUCCESS;
        } catch (FailedToCopyStub|PathNotFoundException $exception) {
            $this->output->writeln($exception->getMessage());
            return Command::FAILURE;
        }
    }
}