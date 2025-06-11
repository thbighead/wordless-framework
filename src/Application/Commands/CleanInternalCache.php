<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\InternalCache;
use Wordless\Core\InternalCache\Exceptions\FailedToCleanInternalCaches;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration;

class CleanInternalCache extends ConsoleCommand
{
    final public const COMMAND_NAME = 'cache:clean';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Clean Wordless internal cache files.';
    }

    protected function help(): string
    {
        return 'Erases all PHP files inside cache directory.';
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
                $this->decorateText('Cleaning internal caches...', Decoration::warning),
                fn() => InternalCache::clean()
            );

            return Command::SUCCESS;
        } catch (FailedToCleanInternalCaches $exception) {
            $this->writelnDanger("Failed! {$exception->getMessage()}");

            return Command::FAILURE;
        }
    }
}
