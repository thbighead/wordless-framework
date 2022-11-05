<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Wordless\Abstractions\Migrations\Script;
use Wordless\Abstractions\StubMounters\MigrationStubMounter;
use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\Command\LoadWpConfig;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class MakeMigration extends WordlessCommand
{
    use LoadWpConfig;

    protected static $defaultName = 'make:migration';

    private const MIGRATION_CLASS_ARGUMENT_NAME = 'snake_cased_migration_class';

    protected function arguments(): array
    {
        return [
            [
                self::ARGUMENT_DESCRIPTION_FIELD =>
                    'The class name of your new migration file in snake case (using _ between words).',
                self::ARGUMENT_MODE_FIELD => InputArgument::REQUIRED,
                self::ARGUMENT_NAME_FIELD => self::MIGRATION_CLASS_ARGUMENT_NAME,
            ],
        ];
    }

    protected function description(): string
    {
        return 'Create a migration script.';
    }

    protected function help(): string
    {
        return 'Creates a migration script file using its class name as base.';
    }

    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $snake_cased_migration_class_name = strtolower(
            $this->input->getArgument(self::MIGRATION_CLASS_ARGUMENT_NAME)
        );
        $migration_class_name = Str::studlyCase($snake_cased_migration_class_name);
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
