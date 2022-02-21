<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Abstractions\Guessers\MigrationClassNameGuesser;
use Wordless\Abstractions\Migrations\Script;
use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\Command\ForceMode;
use Wordless\Contracts\Command\LoadWpConfig;
use Wordless\Exception\FailedToFindExecutedMigrationScript;
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
            $this->mountForceModeOption('Rollback every migration to run all scripts from zero.')
        ];
    }

    /**
     * @return int
     * @throws FailedToFindExecutedMigrationScript
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

    private function executeMissingMigrationsScripts()
    {
        if (empty($this->migrations_missing_execution)) {
            $this->output->writeln('No missing migrations to execute.');
        }

        sort($this->migrations_missing_execution);

        $this->executed_migrations_list[$now = date('Y-m-d H:i:s')] = [];

        foreach ($this->migrations_missing_execution as $missing_migration_filename) {
            $missing_migration_namespaced_class = $this->guessed_migrations_class_names[$missing_migration_filename];

            $this->wrapScriptWithMessages(
                "Executing $missing_migration_namespaced_class::up()...",
                function () use ($missing_migration_filename, $missing_migration_namespaced_class, $now) {
                    include_once $missing_migration_filename;
                    /** @var Script $migrationObject */
                    $migrationObject = new $missing_migration_namespaced_class;
                    $migrationObject->up();
                    $this->executed_migrations_list[$now][] = $missing_migration_filename;
                    update_option(self::MIGRATIONS_WP_OPTION_NAME, serialize($this->executed_migrations_list));
                }
            );
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

    private function getOrderedExecutedMigrationsChunksList(): array
    {
        return array_reverse($this->getExecutedMigrationsChunksList());
    }

    private function getExecutedMigrationsChunksList(): array
    {
        if (isset($this->executed_migrations_list)) {
            return $this->executed_migrations_list;
        }

        return $this->executed_migrations_list = get_option(self::MIGRATIONS_WP_OPTION_NAME, []);
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

    /**
     * @return array
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function getScriptsFilesToClassNamesDictionary(): array
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

    private function resolveForceMode()
    {
        if ($this->isForceMode()) {
            $this->output->writeln(
                'Running migration into force mode. Rolling back every executed migration.'
            );
            foreach ($this->getOrderedExecutedMigrationsChunksList() as $executed_migrations_chunk) {
                foreach (array_reverse($executed_migrations_chunk) as $executed_migration_namespaced_class) {
                    $this->wrapScriptWithMessages(
                        "Executing $executed_migration_namespaced_class::down()...",
                        function () use ($executed_migration_namespaced_class) {
                            /** @var Script $migrationObject */
                            $migrationObject = new $executed_migration_namespaced_class;
                            $migrationObject->down();
                        }
                    );
                }
            }

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
    }
}
