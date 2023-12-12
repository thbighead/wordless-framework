<?php

namespace Wordless\Core\Bootstrapper\Traits;

use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;

trait Migrations
{

    private array $loaded_migrations_filepath = [];
    private array $migrations_objects = [];

    /**
     * @return static
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    public static function bootIntoMigrationCommand(): static
    {
        return self::getInstance()->loadProvidedMigrations()
            ->initializeMigrations();
    }

    /**
     * @return array<string, string>
     */
    public function getLoadedMigrationsFilepath(): array
    {
        return $this->loaded_migrations_filepath;
    }

    private function initializeMigrations(): static
    {
        foreach ($this->loaded_migrations_filepath as $migration_filename => $migration_absolute_filepath) {
            $this->migrations_objects[$migration_filename] = require $migration_absolute_filepath;
        }

        return $this;
    }

    private function loadProvidedMigrations(): static
    {
        foreach ($this->loaded_providers as $provider) {
            foreach ($provider->registerMigrations() as $migration_absolute_filepath) {
                $migration_absolute_filepath = $this->validateMigrationFilepath($migration_absolute_filepath);

                if ($migration_absolute_filepath === null) {
                    continue;
                }

                $migration_filename = Str::afterLast($migration_absolute_filepath, DIRECTORY_SEPARATOR);

                $this->loaded_migrations_filepath[$migration_filename] = $migration_absolute_filepath;
            }
        }

        ksort($this->loaded_migrations_filepath);

        return $this;
    }

    private function validateMigrationFilepath(string $migration_absolute_filepath): ?string
    {
        if (!Str::($migration_absolute_filepath, '.php')) {
            return null;
        }

        try {
            $migration_absolute_filepath = ProjectPath::realpath($migration_absolute_filepath);
        } catch (PathNotFoundException) {
            return null;
        }

        return $migration_absolute_filepath;
    }
}
