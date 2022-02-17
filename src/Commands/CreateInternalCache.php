<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Abstractions\InternalCache;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exception\FailedToCopyStub;
use Wordless\Exception\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class CreateInternalCache extends WordlessCommand
{
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

    /**
     * @return int
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        include_once ProjectPath::wpCore('wp-config.php');

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