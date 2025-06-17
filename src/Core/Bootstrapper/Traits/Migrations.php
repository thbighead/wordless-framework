<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use Generator;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\CannotReadPath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadBootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadErrorReportingConfiguration;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\FailedToBootMigrationCommand;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\FailedToLoadProvidedMigration;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\InvalidMigrationFilename;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\MigrationFileNotFound;

trait Migrations
{
    private array $loaded_migrations_filepath = [];

    /**
     * @return array
     * @throws FailedToBootMigrationCommand
     */
    public static function bootIntoMigrationCommand(): array
    {
        try {
            return self::getInstance()->loadProvidedMigrations()
                ->getLoadedMigrationsFilepath();
        } catch (FailedToLoadBootstrapper|FailedToLoadProvidedMigration $exception) {
            throw new FailedToBootMigrationCommand($exception);
        }
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
     * @throws FailedToLoadProvidedMigration
     */
    private function loadProvidedMigrations(): static
    {
        foreach ($this->loaded_providers as $provider) {
            foreach ($provider->registerMigrations() as $migration_absolute_path) {
                try {
                    foreach ($this->retrieveMigrationFilePathsFrom($migration_absolute_path) as $migration_absolute_filepath) {
                        $migration_absolute_filepath = $this->validateMigrationFilepath($migration_absolute_filepath);

                        $migration_filename = Str::afterLast($migration_absolute_filepath, DIRECTORY_SEPARATOR);

                        $this->validateMigrationFilename($migration_filename);

                        $this->loaded_migrations_filepath[$migration_filename] = $migration_absolute_filepath;
                    }
                } catch (CannotReadPath|InvalidMigrationFilename|MigrationFileNotFound $exception) {
                    throw new FailedToLoadProvidedMigration($provider, $migration_absolute_path, $exception);
                }
            }
        }

        ksort($this->loaded_migrations_filepath);

        return $this;
    }

    /**
     * @param string $migration_absolute_path
     * @return Generator<string>
     * @throws CannotReadPath
     */
    private function retrieveMigrationFilePathsFrom(string $migration_absolute_path): Generator
    {
        return DirectoryFiles::recursiveRead($migration_absolute_path);
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
