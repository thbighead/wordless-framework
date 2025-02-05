<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Core\InternalCache;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

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

    /**
     * @return int
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FormatException
     * @throws InvalidProviderClass
     * @throws PathException
     */
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
