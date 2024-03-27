<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyInvalidArgumentException;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\MigrationStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Migration;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class MakeMigration extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'make:migration';
    private const MIGRATION_FILENAME_ARGUMENT_NAME = 'snake_cased_migration_filename';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::MIGRATION_FILENAME_ARGUMENT_NAME,
                'The name of your new migration file in snake case (using _ between words).',
                ArgumentMode::required
            ),
        ];
    }

    protected function description(): string
    {
        return 'Creates a migration script.';
    }

    protected function help(): string
    {
        return 'Creates a migration script file.';
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
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @throws SymfonyInvalidArgumentException
     */
    protected function runIt(): int
    {
        $snake_cased_migration_class_name = Str::snakeCase(
            (string)$this->input->getArgument(self::MIGRATION_FILENAME_ARGUMENT_NAME)
        );
        $migration_file_name = date(Migration::FILENAME_DATE_FORMAT) . "$snake_cased_migration_class_name.php";

        $this->wrapScriptWithMessages(
            "Creating $migration_file_name...",
            function () use ($migration_file_name) {
                MigrationStubMounter::make(ProjectPath::migrations() . "/$migration_file_name")
                    ->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }
}
