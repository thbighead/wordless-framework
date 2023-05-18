<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Core\InternalCache;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class CreateInternalCache extends ConsoleCommand
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
            $this->writelnError($exception->getMessage());
            return Command::FAILURE;
        }
    }
}
