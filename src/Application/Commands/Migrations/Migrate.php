<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Migrations\Migrate\Traits\ExecutionTimestamp;
use Wordless\Application\Commands\Migrations\Migrate\Traits\ForceMode;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\InvalidMigrationFilename;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\MigrationFileNotFound;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\Migration;

class Migrate extends ConsoleCommand
{
    use ExecutionTimestamp;
    use ForceMode;
    use LoadWpConfig;

    public const COMMAND_NAME = 'migrate';
    final public const MIGRATIONS_WP_OPTION_NAME = 'wordless_migrations_already_executed';
    protected const MIGRATION_METHOD_TO_EXECUTE = 'up';

    /** @var array<string, string[]> $executed_migrations_chunks_list */
    protected array $executed_migrations_chunks_list;
    /** @var array<string, Migration> $filtered_migrations_to_execute */
    private array $filtered_migrations_to_execute;
    /** @var array<string, string> $loaded_migrations */
    private array $loaded_migrations;

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Run missing migration scripts.';
    }

    protected function help(): string
    {
        return 'Checks '
            . self::MIGRATIONS_WP_OPTION_NAME
            . ' option and run every migration script missing from it ordered by filename.';
    }

    protected function options(): array
    {
        return [
            $this->mountForceModeOption('Rollback every migration to run all scripts from zero.'),
        ];
    }

    /**
     * @param string $migration_filename
     * @return void
     * @throws FailedToUpdateOption
     */
    protected function registerMigrationExecution(string $migration_filename): void
    {
        if (!isset($this->getExecutedMigrationsChunksList()[$this->getNow()])) {
            $this->executed_migrations_chunks_list[$this->getNow()] = [];
        }

        $this->executed_migrations_chunks_list[$this->getNow()][] = $migration_filename;

        $this->updateExecutedMigrationsListOption();
    }

    /**
     * @return int
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws ExceptionInterface
     * @throws FailedToUpdateOption
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->resolveForceMode()
            ->filterMigrationsMissingExecution()
            ->executeFilteredMigrations();

        return Command::SUCCESS;
    }

    /**
     * @return void
     * @throws FailedToUpdateOption
     */
    final protected function executeFilteredMigrations(): void
    {
        if (empty($this->filtered_migrations_to_execute)) {
            $this->writelnInfo('No migrations to execute.');

            return;
        }

        foreach ($this->filtered_migrations_to_execute as $migration_filename => $filteredMigration) {
            $this->wrapScriptWithMessages(
                "Executing $migration_filename::" . static::MIGRATION_METHOD_TO_EXECUTE . '()...',
                function () use ($migration_filename, $filteredMigration) {
                    $this->executeMigration($filteredMigration)
                        ->registerMigrationExecution($migration_filename);
                }
            );
        }
    }

    final protected function executeMigration(Migration $migration): static
    {
        $migration->{static::MIGRATION_METHOD_TO_EXECUTE}();

        return $this;
    }

    /**
     * @return array<string, string[]>
     */
    final protected function getExecutedMigrationsChunksList(): array
    {
        return $this->executed_migrations_chunks_list ??
            $this->executed_migrations_chunks_list = Option::get(self::MIGRATIONS_WP_OPTION_NAME, []);
    }

    /**
     * @return array<string, string>
     * @throws EmptyConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     * @throws FormatException
     * @throws DotEnvNotSetException
     */
    final protected function getLoadedMigrations(): array
    {
        return $this->loaded_migrations ?? $this->loaded_migrations = Bootstrapper::bootIntoMigrationCommand();
    }

    /**
     * @return array<string, string[]>
     */
    final protected function getMigrationChunksOrderedDescending(): array
    {
        $migrations_chunks_list_ordered_descending = [];
        $executed_migrations_chunks_list = $this->getExecutedMigrationsChunksList();

        ksort($executed_migrations_chunks_list);

        $executed_migrations_chunks_list = array_reverse($executed_migrations_chunks_list, true);

        foreach ($executed_migrations_chunks_list as $execution_datetime => $migration_chunk) {
            $migrations_chunks_list_ordered_descending[$execution_datetime] = array_reverse($migration_chunk);
        }

        return $migrations_chunks_list_ordered_descending;
    }

    /**
     * @param array<string, string> $filtered_migrations
     * @return $this
     */
    final protected function instantiateFilteredMigrations(array $filtered_migrations): static
    {
        $this->filtered_migrations_to_execute = [];

        foreach ($filtered_migrations as $filtered_migration_filename => $filtered_migration_absolute_filepath) {
            $this->filtered_migrations_to_execute[$filtered_migration_filename] =
                require $filtered_migration_absolute_filepath;
        }

        return $this;
    }

    /**
     * @return void
     * @throws FailedToUpdateOption
     */
    final protected function updateExecutedMigrationsListOption(): void
    {
        Option::updateOrFail(self::MIGRATIONS_WP_OPTION_NAME, $this->getExecutedMigrationsChunksList());
    }

    /**
     * @return $this
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    private function filterMigrationsMissingExecution(): static
    {
        $filtered_migrations = [];

        foreach ($this->getLoadedMigrations() as $migration_filename => $migration_absolute_filepath) {
            if ($this->isMigrationFileAlreadyExecuted($migration_filename)) {
                continue;
            }

            $filtered_migrations[$migration_filename] = $migration_absolute_filepath;
        }

        return $this->instantiateFilteredMigrations($filtered_migrations);
    }

    private function isMigrationFileAlreadyExecuted(string $migration_filename): bool
    {
        foreach ($this->getExecutedMigrationsChunksList() as $chunk) {
            if (Arr::hasValue($chunk, $migration_filename)) {
                return true;
            }
        }

        return false;
    }
}
