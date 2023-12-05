<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\MigrationStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Migration\Script;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class MakeMigration extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'make:migration';
    private const MIGRATION_CLASS_ARGUMENT_NAME = 'snake_cased_migration_class';

    protected static $defaultName = self::COMMAND_NAME;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            new ArgumentDTO(
                self::MIGRATION_CLASS_ARGUMENT_NAME,
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
        return 'Creates a migration script file using its name to guess the generated class name.';
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
     * @throws PathNotFoundException
     * @throws InvalidDirectory
     */
    protected function runIt(): int
    {
        $snake_cased_migration_class_name = Str::snakeCase(
            $this->input->getArgument(self::MIGRATION_CLASS_ARGUMENT_NAME)
        );
        $migration_class_name = Str::pascalCase($snake_cased_migration_class_name);
        $migration_file_name = date(Script::FILENAME_DATE_FORMAT) . "$snake_cased_migration_class_name.php";

        $this->wrapScriptWithMessages(
            "Creating $migration_file_name...",
            function () use ($migration_class_name, $migration_file_name) {
                (new MigrationStubMounter(ProjectPath::migrations() . "/$migration_file_name"))
                    ->setReplaceContentDictionary(['DummyMigration' => $migration_class_name])
                    ->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }
}
