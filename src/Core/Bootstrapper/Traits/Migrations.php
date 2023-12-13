<?php

namespace Wordless\Core\Bootstrapper\Traits;

use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\InvalidMigrationFilename;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\MigrationFileNotFound;

trait Migrations
{
    private array $loaded_migrations_filepath = [];

    /**
     * @return array<string, string>
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    public static function bootIntoMigrationCommand(): array
    {
        return self::getInstance()->loadProvidedMigrations()
            ->getLoadedMigrationsFilepath();
    }

    /**
     * @return array<string, string>
     */
    private function getLoadedMigrationsFilepath(): array
    {
        return $this->loaded_migrations_filepath;
    }

    /**
     * @return $this
     * @throws InvalidMigrationFilename
     * @throws MigrationFileNotFound
     */
    private function loadProvidedMigrations(): static
    {
        foreach ($this->loaded_providers as $provider) {
            foreach ($provider->registerMigrations() as $migration_absolute_filepath) {
                $migration_absolute_filepath = $this->validateMigrationFilepath($migration_absolute_filepath);

                $migration_filename = Str::afterLast($migration_absolute_filepath, DIRECTORY_SEPARATOR);

                $this->validateMigrationFilename($migration_filename);

                $this->loaded_migrations_filepath[$migration_filename] = $migration_absolute_filepath;
            }
        }

        ksort($this->loaded_migrations_filepath);

        return $this;
    }

    /**
     * @param string $migration_filename
     * @return void
     * @throws InvalidMigrationFilename
     */
    private function validateMigrationFilename(string $migration_filename): void
    {
        if (!preg_match('/^[1-9]\d{3}_[0-1]\d_[0-3]\d_\d{6}_\w+\.php$/', $migration_filename)) {
            throw new InvalidMigrationFilename($migration_filename);
        }
    }

    /**
     * @param string $migration_absolute_filepath
     * @return string
     * @throws MigrationFileNotFound
     */
    private function validateMigrationFilepath(string $migration_absolute_filepath): string
    {
        try {
            $migration_absolute_filepath = ProjectPath::realpath($migration_absolute_filepath);
        } catch (PathNotFoundException) {
            throw new MigrationFileNotFound($migration_absolute_filepath);
        }

        return $migration_absolute_filepath;
    }
}
