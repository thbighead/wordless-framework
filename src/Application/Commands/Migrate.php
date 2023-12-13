<?php

namespace Wordless\Application\Commands;

use Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Migrate\Exceptions\FailedToFindExecutedMigrationScript;
use Wordless\Application\Commands\Migrate\Exceptions\FailedToFindMigrationScript;
use Wordless\Application\Commands\Migrate\Traits\ExecutionTimestamp;
use Wordless\Application\Commands\Migrate\Traits\ForceMode;
use Wordless\Application\Commands\Migrate\Traits\MissingMigrationsCalculator;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\InvalidMigrationFilename;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\MigrationFileNotFound;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Migration\Script;

class Migrate extends ConsoleCommand
{
    use ExecutionTimestamp;
    use ForceMode;
    use LoadWpConfig;
    use MissingMigrationsCalculator;

    public const COMMAND_NAME = 'migrate';
    final public const MIGRATIONS_WP_OPTION_NAME = 'wordless_migrations_already_executed';

    protected static $defaultName = self::COMMAND_NAME;

    /** @var array<string, string> $migrations_paths_provided */
    private array $migrations_paths_provided;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Run missing migration scripts.';
    }

    /**
     * @param string $migration_filename
     * @param bool $up
     * @return void
     * @throws FailedToFindExecutedMigrationScript
     * @throws FailedToFindMigrationScript
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    protected function executeMigrationScriptFile(string $migration_filename, bool $up = true): void
    {
        $migration_class_name = $this->getLoadedMigrationsClasses()[$migration_filename] ?? null;

        if ($migration_class_name === null) {
            throw new FailedToFindMigrationScript($migration_filename);
        }

        $migration_method_to_call = $up ? 'up' : 'down';

        $this->wrapScriptWithMessages(
            "Executing $migration_class_name::$migration_method_to_call()...",
            function () use ($migration_filename, $migration_class_name, $migration_method_to_call) {
                include_once ProjectPath::migrations($migration_filename);
                /** @var Script $migrationObject */
                $migrationObject = new $migration_class_name;
                $migrationObject->$migration_method_to_call();

                if ($migration_method_to_call === 'down') {
                    $this->removeFromExecutedMigrationsListOption($migration_filename);
                    return;
                }

                $this->addToExecutedMigrationsListOption($migration_filename);
            }
        );
    }

    protected function executedMigrationsOrderedByExecutionDescending(): Generator
    {
        foreach (array_reverse($this->getExecutedMigrationsChunksList()) as $chunk) {
            foreach (array_reverse($chunk) as $migration_filename) {
                yield $migration_filename;
            }
        }
    }

    protected function help(): string
    {
        return 'Checks '
            . self::MIGRATIONS_WP_OPTION_NAME
            . ' option and run every migration script missing from it ordered by filename.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            $this->mountForceModeOption('Rollback every migration to run all scripts from zero.'),
        ];
    }

    /**
     * @return int
     * @throws FailedToFindExecutedMigrationScript
     * @throws FailedToFindMigrationScript
     * @throws InvalidArgumentException
     * @throws InvalidConfigKey
     * @throws InvalidDirectory
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->resolveForceMode()
            ->filterMigrationsMissingExecution()
            ->executeMissingMigrationsScripts();

        return Command::SUCCESS;
    }

    /**
     * @param string $migration_filename
     * @return void
     * @throws FailedToUpdateOption
     */
    private function addToExecutedMigrationsListOption(string $migration_filename): void
    {
        if (!isset($this->executed_migrations_list[$this->getNow()])) {
            $this->executed_migrations_list[$this->getNow()] = [];
        }

        $this->executed_migrations_list[$this->getNow()][] = $migration_filename;

        $this->updateExecutedMigrationsListOption();
    }

    /**
     * @return void
     * @throws FailedToFindMigrationScript
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function executeMissingMigrationsScripts(): void
    {
        if (empty($this->migrations_missing_execution)) {
            $this->writelnInfo('No missing migrations to execute.');
            return;
        }

        sort($this->migrations_missing_execution);

        foreach ($this->migrations_missing_execution as $missing_migration_filename) {
            $this->executeMigrationScriptFile($missing_migration_filename);
        }
    }

    private function getExecutedMigrationsChunksList(): array
    {
        return $this->executed_migrations_list ??
            $this->executed_migrations_list = Option::get(self::MIGRATIONS_WP_OPTION_NAME, []);
    }

    /**
     * @param string $migration_filename
     * @return void
     * @throws FailedToFindExecutedMigrationScript
     * @throws FailedToUpdateOption
     */
    private function removeFromExecutedMigrationsListOption(string $migration_filename): void
    {
        $removed_migration = null;

        foreach ($this->executed_migrations_list as $chunk_key => $migration_chunk) {
            foreach ($migration_chunk as $file_key => $executed_migration_filename) {
                if ($executed_migration_filename === $migration_filename) {
                    $removed_migration = $this->executed_migrations_list[$chunk_key][$file_key];
                    unset($this->executed_migrations_list[$chunk_key][$file_key]);
                    if (empty($this->executed_migrations_list[$chunk_key])) {
                        unset($this->executed_migrations_list[$chunk_key]);
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
     * @return void
     * @throws FailedToUpdateOption
     */
    private function updateExecutedMigrationsListOption(): void
    {
        Option::update(self::MIGRATIONS_WP_OPTION_NAME, $this->executed_migrations_list);
    }

    private function initializeMigrations(): static
    {
        foreach ($this->getMigrationsPathsProvided() as $migration_filename => $migration_absolute_filepath) {
            $this->migrations_missing_execution[$migration_filename] = require $migration_absolute_filepath;
        }

        return $this;
    }

    /**
     * @return array<string, string>
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    private function getMigrationsPathsProvided(): array
    {
        return $this->migrations_paths_provided ??
            $this->migrations_paths_provided = Bootstrapper::bootIntoMigrationCommand();
    }
}
