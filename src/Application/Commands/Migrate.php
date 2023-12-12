<?php

namespace Wordless\Application\Commands;

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
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
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

    private array $executed_migrations_list;
    private array $loaded_migrations_classes;
    private array $migrations_missing_execution;
    private array $migration_files;

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

    protected function getOrderedExecutedMigrationsChunksList(): array
    {
        return array_reverse($this->getExecutedMigrationsChunksList());
    }

    /**
     * @return array
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    protected function getLoadedMigrationsClasses(): array
    {
        if (isset($this->loaded_migrations_classes)) {
            return $this->loaded_migrations_classes;
        }

        return $this->loaded_migrations_classes = Bootstrapper::bootIntoMigrationCommand()
            ->getLoadedMigrationsFilepath();
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
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     * @throws InvalidArgumentException
     */
    protected function runIt(): int
    {
        $this->resolveForceMode()
            ->filterMigrationsMissingExecution();
        $this->executeMissingMigrationsScripts();

        return Command::SUCCESS;
    }

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

    /**
     * @return void
     * @throws FailedToFindExecutedMigrationScript
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function filterMigrationsMissingExecution(): void
    {
        $this->migrations_missing_execution = $this->getLoadedMigrationsClasses();

        foreach ($this->getExecutedMigrationsChunksList() as $executed_migrations_chunk) {
            foreach ($executed_migrations_chunk as $executed_migration_filename) {
                $migration_namespaced_class =
                    $this->migrations_missing_execution[$executed_migration_filename] ?? null;
                if ($migration_namespaced_class === null) {
                    throw new FailedToFindExecutedMigrationScript($executed_migration_filename);
                }

                unset($this->migrations_missing_execution[$executed_migration_filename]);
            }
        }

        $this->migrations_missing_execution = array_keys($this->migrations_missing_execution);
    }

    private function getExecutedMigrationsChunksList(): array
    {
        return $this->executed_migrations_list ??
            $this->executed_migrations_list = get_option(self::MIGRATIONS_WP_OPTION_NAME, []);
    }

    /**
     * @return array
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    private function getMigrationFiles(): array
    {
        return $this->migration_files ?? $this->migration_files = $this->listMigrationFiles();
    }

    /**
     * @return array
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     * @throws InvalidConfigKey
     */
    private function listMigrationFiles(): array
    {
        return Bootstrapper::getInstance()->getLoadedMigrationsFilepath();
    }

    /**
     * @param string $migration_filename
     * @return void
     * @throws FailedToFindExecutedMigrationScript
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

    private function updateExecutedMigrationsListOption(): bool
    {
        return update_option(self::MIGRATIONS_WP_OPTION_NAME, $this->executed_migrations_list);
    }
}
