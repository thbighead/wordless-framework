<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Exceptions\FailedToFindMigrationScript;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;

class FlushMigrations extends Migrate
{
    protected static $defaultName = 'migrate:flush';

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Erases ' . self::MIGRATIONS_WP_OPTION_NAME . '.';
    }

    protected function help(): string
    {
        return "{$this->description()} If migrations aren't found they still are going to be excluded from database.";
    }

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
        foreach ($this->getOrderedExecutedMigrationsChunksList() as $migration_filename => $migration_class_name) {
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
