<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Abstractions\InternalCache;
use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\Command\LoadWpConfig;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;

class CreateInternalCache extends WordlessCommand
{
    use LoadWpConfig;

    protected static $defaultName = self::COMMAND_NAME;

    public const COMMAND_NAME = 'cache:create';

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
