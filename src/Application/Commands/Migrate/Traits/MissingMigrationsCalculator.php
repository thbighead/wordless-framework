<?php

namespace Wordless\Application\Commands\Migrate\Traits;

use Wordless\Application\Commands\Migrate\Exceptions\FailedToFindExecutedMigrationScript;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
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
     * @return $this
     * @throws FailedToFindExecutedMigrationScript
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    private function filterMigrationsMissingExecution(): static
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

        return $this;
    }
}
