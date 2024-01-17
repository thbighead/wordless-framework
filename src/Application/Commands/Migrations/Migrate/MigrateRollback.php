<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations\Migrate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Migrations\Migrate;
use Wordless\Application\Commands\Migrations\Migrate\Exceptions\FailedToFindExecutedMigrationScript;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\InvalidMigrationFilename;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\MigrationFileNotFound;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

class MigrateRollback extends Migrate
{
    public const COMMAND_NAME = 'migrate:rollback';
    final protected const MIGRATION_METHOD_TO_EXECUTE = 'down';
    private const NUMBER_OF_CHUNKS_OPTION = 'chunks';
    private const ALL_CHUNKS_VALUE = 'all';

    private int $number_of_chunks;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Execute the last migration chunk down method to undo its changes.';
    }

    protected function help(): string
    {
        return 'Undo all changes made by migrations at the last chunks you define (default is just the last one).';
    }

    protected function options(): array
    {
        return [
            OptionDTO::make(
                self::NUMBER_OF_CHUNKS_OPTION,
                'How many chunks you want to rollback. Default is 1.',
                mode: OptionMode::optional_value,
                default: self::ALL_CHUNKS_VALUE
            ),
        ];
    }

    /**
     * @return int
     * @throws FailedToFindExecutedMigrationScript
     * @throws FailedToUpdateOption
     * @throws InvalidArgumentException
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->filterExecutedMigrationsToRollback()
            ->executeFilteredMigrations();

        return Command::SUCCESS;
    }

    /**
     * @param string $migration_filename
     * @return string
     * @throws FailedToFindExecutedMigrationScript
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    final protected function findLoadedMigrationFilepathByFilename(string $migration_filename): string
    {
        return $this->getLoadedMigrations()[$migration_filename]
            ?? throw new FailedToFindExecutedMigrationScript($migration_filename);
    }

    /**
     * @param string $migration_filename
     * @return void
     * @throws FailedToFindExecutedMigrationScript
     * @throws FailedToUpdateOption
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    final protected function registerMigrationExecution(string $migration_filename): void
    {
        $removed_migration = null;

        foreach ($this->getExecutedMigrationsChunksList() as $execution_datetime => $migration_chunk) {
            foreach ($migration_chunk as $index => $executed_migration_filename) {
                if ($executed_migration_filename === $migration_filename) {
                    $removed_migration = $this->findLoadedMigrationFilepathByFilename($executed_migration_filename);

                    unset($this->executed_migrations_chunks_list[$execution_datetime][$index]);

                    if (empty($this->executed_migrations_chunks_list[$execution_datetime])) {
                        unset($this->executed_migrations_chunks_list[$execution_datetime]);
                    }

                    break;
                }
            }
        }

        if ($removed_migration === null) {
            throw new FailedToFindExecutedMigrationScript($migration_filename);
        }

        $this->updateExecutedMigrationsListOption();
    }

    /**
     * @return $this
     * @throws FailedToFindExecutedMigrationScript
     * @throws InvalidArgumentException
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    private function filterExecutedMigrationsToRollback(): static
    {
        $filtered_migrations = [];

        foreach ($this->getFilteredMigrationChunksOrderedDescending() as $executed_migration_chunk) {
            foreach ($executed_migration_chunk as $executed_migration_filename) {
                $filtered_migrations[$executed_migration_filename] =
                    $this->findLoadedMigrationFilepathByFilename($executed_migration_filename);
            }
        }

        return $this->instantiateFilteredMigrations($filtered_migrations);
    }

    /**
     * @return array<string[]>
     * @throws InvalidArgumentException
     */
    private function getFilteredMigrationChunksOrderedDescending(): array
    {
        $migrations_chunks_list = $this->getMigrationChunksOrderedDescending();

        return array_slice(
            $this->getMigrationChunksOrderedDescending(),
            0,
            $this->getNumberOfChunks(count($migrations_chunks_list))
        );
    }

    /**
     * @param int $max
     * @return int
     * @throws InvalidArgumentException
     */
    private function getNumberOfChunks(int $max): int
    {
        if (isset($this->number_of_chunks)) {
            return $this->number_of_chunks;
        }

        $chunks_input_option_value = (string)$this->input->getOption(self::NUMBER_OF_CHUNKS_OPTION);

        if (Str::lower($chunks_input_option_value) === self::ALL_CHUNKS_VALUE) {
            return $max;
        }

        return $this->number_of_chunks = max((int)$chunks_input_option_value, 1);
    }
}
