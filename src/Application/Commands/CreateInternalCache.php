<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Core\InternalCache;
use Wordless\Core\InternalCache\Exceptions\FailedToGenerateInternalCacheFile;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class CreateInternalCache extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'cache:create';

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
        } catch (FailedToGenerateInternalCacheFile $exception) {
            $this->writelnDanger($exception->getMessage());

            return Command::FAILURE;
        }
    }
}
