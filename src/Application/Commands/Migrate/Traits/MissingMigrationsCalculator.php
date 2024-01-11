<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrate\Traits;

use Wordless\Application\Commands\Migrate\Exceptions\FailedToFindExecutedMigrationScript;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\InvalidMigrationFilename;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\MigrationFileNotFound;
use Wordless\Infrastructure\Migration\Script;

trait MissingMigrationsCalculator
{
    /** @var array<string, string[]> $executed_migrations_list */
    private array $executed_migrations_list;
    /** @var array<string, Script> $migrations_missing_execution */
    private array $migrations_missing_execution;

    /**
     * @return void
     * @throws FailedToFindExecutedMigrationScript
     * @throws FailedToUpdateOption
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    private function executeMissingMigrationsScripts(): void
    {
        if (empty($this->getMigrationsMissingExecution())) {
            $this->writelnInfo('No missing migrations to execute.');
            return;
        }

        foreach ($this->getMigrationsMissingExecution() as $missing_migration_filename => $missingMigrationScript) {
            $this->executeMigrationScriptFile($missing_migration_filename);
        }
    }

    /**
     * @return $this
     * @throws FailedToFindExecutedMigrationScript
     */
    private function filterMigrationsMissingExecution(): static
    {
        foreach ($this->executedMigrationsOrderedByExecutionDescending() as $executed_migration_filename) {
            $migration_namespaced_class =
                $this->migrations_missing_execution[$executed_migration_filename] ?? null;

            if ($migration_namespaced_class === null) {
                throw new FailedToFindExecutedMigrationScript($executed_migration_filename);
            }

            unset($this->migrations_missing_execution[$executed_migration_filename]);
        }

        ksort($this->migrations_missing_execution);

        return $this;
    }

    /**
     * @return array<string, Script>
     * @throws FailedToFindExecutedMigrationScript
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    private function getMigrationsMissingExecution(): array
    {
        if (isset($this->migrations_missing_execution)) {
            return $this->migrations_missing_execution;
        }

        $this->migrations_missing_execution = $this->getMigrationsPathsProvided();

        return $this->filterMigrationsMissingExecution()
            ->instantiateMigrationScripts();
    }

    /**
     * @return array<string, Script>
     */
    private function instantiateMigrationScripts(): array
    {
        foreach ($this->migrations_missing_execution as &$migration_absolute_filepath) {
            $migration_absolute_filepath = require $migration_absolute_filepath;
        }

        return $this->migrations_missing_execution;
    }
}
