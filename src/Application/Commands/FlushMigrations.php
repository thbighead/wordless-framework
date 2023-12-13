<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Migrate\Exceptions\FailedToFindMigrationScript;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class FlushMigrations extends Migrate
{
    final public const COMMAND_NAME = 'migrate:flush';

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
        return 'Erases ' . static::MIGRATIONS_WP_OPTION_NAME . '.';
    }

    protected function help(): string
    {
        return 'If migrations aren\'t found they still are going to be excluded from database.';
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
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        foreach ($this->executedMigrationsOrderedByExecutionDescending() as $migration_filename) {
            try {
                $this->executeMigrationScriptFile($migration_filename, false);
            } catch (FailedToFindMigrationScript $exception) {
                $this->writelnComment("{$exception->getMessage()} Skipping.");
            }
        }

        $this->trashMigrationsOption();

        return Command::SUCCESS;
    }
}
