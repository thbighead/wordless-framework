<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\InternalCache;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class CreateInternalCache extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'cache:create';

    protected static $defaultName = self::COMMAND_NAME;

    /**
     * @return ArgumentDTO[]
     */
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
        return 'This avoid uses of reflections and calculations throughout system booting.';
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
        try {
            $this->wrapScriptWithMessages(
                'Generating internal caches...',
                fn() => InternalCache::generate()
            );

            return Command::SUCCESS;
        } catch (FailedToCopyStub|PathNotFoundException $exception) {
            $this->writelnDanger($exception->getMessage());

            return Command::FAILURE;
        }
    }
}
