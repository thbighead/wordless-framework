<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations\Migrate;

use Generator;
use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Migrations\Migrate\Exceptions\FailedToFindExecutedMigrationScript;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\InvalidMigrationFilename;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\MigrationFileNotFound;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class FlushMigrations extends MigrateRollback
{
    final public const COMMAND_NAME = 'migrate:flush';

    /**
     * @return ArgumentDTO[]
     */
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
     * @throws FailedToUpdateOption
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->flushExecutedMigrations()
            ->trashMigrationsOption();

        return Command::SUCCESS;
    }

    /**
     * @return Generator<string>
     */
    private function executedMigrationsOrderedByExecutionDescending(): Generator
    {
        foreach ($this->getMigrationChunksOrderedDescending() as $migration_chunk) {
            foreach ($migration_chunk as $executed_migration_filename) {
                yield $executed_migration_filename;
            }
        }
    }

    /**
     * @return $this
     * @throws PathNotFoundException
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws InvalidMigrationFilename
     * @throws MigrationFileNotFound
     */
    private function flushExecutedMigrations(): static
    {
        foreach ($this->executedMigrationsOrderedByExecutionDescending() as $executed_migration_filename) {
            try {
                $this->executeMigration(
                    require $this->findLoadedMigrationFilepathByFilename($executed_migration_filename)
                );
            } catch (FailedToFindExecutedMigrationScript $exception) {
                $this->writelnComment("{$exception->getMessage()} Skipping.");
            }
        }

        return $this;
    }

    /**
     * @return void
     * @throws FailedToUpdateOption
     */
    private function trashMigrationsOption(): void
    {
        $this->wrapScriptWithMessages(
            'Trashing ' . self::MIGRATIONS_WP_OPTION_NAME . ' option...',
            function () {
                Option::update(self::MIGRATIONS_WP_OPTION_NAME, []);
            }
        );
    }
}
