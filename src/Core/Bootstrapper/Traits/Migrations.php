<?php

namespace Wordless\Core\Bootstrapper\Traits;

use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;

trait Migrations
{
    private const PHP_EXTENSION = '.php';

    private array $loaded_migrations_filepath = [];

    /**
     * @return void
     * @throws PathNotFoundException
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     */
    public static function bootIntoMigrationCommand(): void
    {
        self::getInstance()->loadProvidedMigrations();
    }

    /**
     * @return array<string, string>
     */
    public function getLoadedMigrationsFilepath(): array
    {
        return $this->loaded_migrations_filepath;
    }

    private function loadProvidedMigrations(): static
    {
        foreach ($this->loaded_providers as $provider) {
            foreach ($provider->registerMigrations() as $migration_absolute_filepath) {
                $migration_absolute_filepath = $this->validateMigrationFilepath($migration_absolute_filepath);

                if ($migration_absolute_filepath === null) {
                    continue;
                }

                $migration_filename_without_extension = Str::beforeLast(Str::afterLast(
                    $migration_absolute_filepath,
                    DIRECTORY_SEPARATOR
                ), self::PHP_EXTENSION);

                $this->loaded_migrations_filepath[$migration_filename_without_extension] =
                    $migration_absolute_filepath;
            }
        }

        ksort($this->loaded_migrations_filepath);

        return $this;
    }

    private function validateMigrationFilepath(string $migration_absolute_filepath): ?string
    {
        if (!Str::endsWith($migration_absolute_filepath, self::PHP_EXTENSION)) {
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
