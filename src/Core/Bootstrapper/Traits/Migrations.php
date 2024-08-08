<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use Generator;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\InvalidMigrationFilename;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\MigrationFileNotFound;
use Wordless\Core\Exceptions\DotEnvNotSetException;

trait Migrations
{
    private array $loaded_migrations_filepath = [];

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
     * @throws InvalidDirectory
     * @throws InvalidMigrationFilename
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    private function loadProvidedMigrations(): static
    {
        foreach ($this->loaded_providers as $provider) {
            foreach ($provider->registerMigrations() as $migration_absolute_path) {
                foreach ($this->retrieveMigrationFilePathsFrom($migration_absolute_path) as $migration_absolute_filepath) {
                    $migration_absolute_filepath = $this->validateMigrationFilepath($migration_absolute_filepath);

                    $migration_filename = Str::afterLast($migration_absolute_filepath, DIRECTORY_SEPARATOR);

                    $this->validateMigrationFilename($migration_filename);

                    $this->loaded_migrations_filepath[$migration_filename] = $migration_absolute_filepath;
                }
            }
        }

        ksort($this->loaded_migrations_filepath);

        return $this;
    }

    /**
     * @param string $migration_absolute_path
     * @return Generator
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function retrieveMigrationFilePathsFrom(string $migration_absolute_path): Generator
    {
        if (is_dir($migration_absolute_path)) {
            return DirectoryFiles::recursiveRead($migration_absolute_path);
        }

        yield $migration_absolute_path;
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
