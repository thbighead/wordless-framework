<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Abstractions\Guessers\MigrationClassNameGuesser;
use Wordless\Abstractions\Migrations\Script;
use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\Command\ForceMode;
use Wordless\Contracts\Command\LoadWpConfig;
use Wordless\Exception\FailedToFindExecutedMigrationScript;
use Wordless\Exception\FailedToFindMigrationScript;
use Wordless\Exception\InvalidDirectory;
use Wordless\Exception\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class Migrate extends WordlessCommand
{
    use ForceMode, LoadWpConfig;

    protected static $defaultName = 'migrate';

    public const MIGRATIONS_WP_OPTION_NAME = 'wordless_migrations_already_executed';
    protected const FORCE_MODE = 'force';

    private array $executed_migrations_list;
    private array $guessed_migrations_class_names;
    private array $migrations_missing_execution;
    private array $migration_files;
    private string $now;

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
        $migration_class_name = $this->getScriptsFilesToClassNamesDictionary()[$migration_filename] ?? null;

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
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    protected function getScriptsFilesToClassNamesDictionary(): array
    {
        if (isset($this->guessed_migrations_class_names)) {
            return $this->guessed_migrations_class_names;
        }

        $this->guessed_migrations_class_names = [];
        $migrationClassNameGuesser = new MigrationClassNameGuesser;

        foreach ($this->getMigrationFiles() as $migration_filename) {
            $guessed_class_name = $migrationClassNameGuesser->setMigrationFilename($migration_filename)->getValue();
            $this->guessed_migrations_class_names[$migration_filename] = $guessed_class_name;
            $migrationClassNameGuesser->resetGuessedValue();
        }

        return $this->guessed_migrations_class_names;
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
            $this->mountForceModeOption('Rollback every migration to run all scripts from zero.')
        ];
    }

    /**
     * @return int
     * @throws FailedToFindExecutedMigrationScript
     * @throws FailedToFindMigrationScript
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->resolveForceMode();
        $this->filterMigrationsMissingExecution();
        $this->executeMissingMigrationsScripts();

        return Command::SUCCESS;
    }

    protected function trashMigrationsOption()
    {
        $this->wrapScriptWithMessages(
            'Trashing ' . self::MIGRATIONS_WP_OPTION_NAME . '...',
            function () {
                update_option(
                    self::MIGRATIONS_WP_OPTION_NAME,
                    serialize($this->executed_migrations_list = [])
                );
            }
        );
    }

    private function addToExecutedMigrationsListOption(string $migration_filename)
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
    private function executeMissingMigrationsScripts()
    {
        if (empty($this->migrations_missing_execution)) {
            $this->output->writeln('No missing migrations to execute.');
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
    private function filterMigrationsMissingExecution()
    {
        $this->migrations_missing_execution = $this->getScriptsFilesToClassNamesDictionary();

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
        if (isset($this->executed_migrations_list)) {
            return $this->executed_migrations_list;
        }

        return $this->executed_migrations_list = unserialize(get_option(self::MIGRATIONS_WP_OPTION_NAME, []));
    }

    /**
     * @return array
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function getMigrationFiles(): array
    {
        if (isset($this->migration_files)) {
            return $this->migration_files;
        }

        return $this->migration_files = array_filter(
            DirectoryFiles::listFromDirectory(ProjectPath::migrations()),
            function ($supposed_migration_file) {
                return Str::endsWith($supposed_migration_file, '.php');
            }
        );
    }

    private function getNow(): string
    {
        if (isset($this->now)) {
            return $this->now;
        }

        return $this->now = date('Y-m-d H:i:s');
    }

    /**
     * @param string $migration_filename
     * @return void
     * @throws FailedToFindExecutedMigrationScript
     */
    private function removeFromExecutedMigrationsListOption(string $migration_filename)
    {
        $removed_migration = null;

        foreach ($this->executed_migrations_list as $chunk_key => $migration_chunk) {
            foreach ($migration_chunk as $file_key => $executed_migration_filename) {
                if ($executed_migration_filename === $migration_filename) {
                    $removed_migration = $this->executed_migrations_list[$chunk_key][$file_key];
                    unset($this->executed_migrations_list[$chunk_key][$file_key]);
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
     * @throws FailedToFindMigrationScript
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function resolveForceMode()
    {
        if ($this->isForceMode()) {
            $this->output->writeln(
                'Running migration into force mode. Rolling back every executed migration.'
            );
            foreach ($this->getOrderedExecutedMigrationsChunksList() as $executed_migrations_chunk) {
                foreach (array_reverse($executed_migrations_chunk) as $executed_migration_filename) {
                    $this->executeMigrationScriptFile($executed_migration_filename, false);
                }
            }

            $this->trashMigrationsOption();
        }
    }

    private function updateExecutedMigrationsListOption(): bool
    {
        return update_option(self::MIGRATIONS_WP_OPTION_NAME, serialize($this->executed_migrations_list));
    }
}
