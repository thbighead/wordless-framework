<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations;

use Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Migrations\Migrate\Exceptions\FailedToFindExecutedMigrationScript;
use Wordless\Application\Commands\Migrations\Migrate\Exceptions\FailedToFindMigrationScript;
use Wordless\Application\Commands\Migrations\Migrate\Traits\ExecutionTimestamp;
use Wordless\Application\Commands\Migrations\Migrate\Traits\ForceMode;
use Wordless\Application\Commands\Migrations\Migrate\Traits\MissingMigrationsCalculator;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\InvalidMigrationFilename;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\MigrationFileNotFound;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class Migrate extends ConsoleCommand
{
    use ExecutionTimestamp;
    use ForceMode;
    use LoadWpConfig;
    use MissingMigrationsCalculator;

    public const COMMAND_NAME = 'migrate';
    final public const MIGRATIONS_WP_OPTION_NAME = 'wordless_migrations_already_executed';

    /** @var array<string, string> $migrations_paths_provided */
    private array $migrations_paths_provided;

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
     * @throws FailedToUpdateOption
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    protected function executeMigrationScriptFile(string $migration_filename, bool $up = true): void
    {
        $migration_method_to_call = $up ? 'up' : 'down';

        $this->wrapScriptWithMessages(
            'Executing '
            . $this->getMigrationsMissingExecution()[$migration_filename]::class
            . "::$migration_method_to_call()...",
            function () use ($migration_filename, $migration_method_to_call) {
                $this->getMigrationsMissingExecution()[$migration_filename]->$migration_method_to_call();

                if ($migration_method_to_call === 'down') {
                    $this->removeFromExecutedMigrationsListOption($migration_filename);
                    return;
                }

                $this->addToExecutedMigrationsListOption($migration_filename);
            }
        );
    }

    protected function executedMigrationsOrderedByExecutionDescending(): Generator
    {
        foreach (array_reverse($this->getExecutedMigrationsChunksList()) as $chunk) {
            foreach (array_reverse($chunk) as $migration_filename) {
                yield $migration_filename;
            }
        }
    }

    protected function getExecutedMigrationsChunksList(): array
    {
        return $this->executed_migrations_list ??
            $this->executed_migrations_list = Option::get(self::MIGRATIONS_WP_OPTION_NAME, []);
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
     * @throws FailedToUpdateOption
     * @throws InvalidArgumentException
     * @throws InvalidConfigKey
     * @throws InvalidDirectory
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->resolveForceMode()
            ->executeMissingMigrationsScripts();

        return Command::SUCCESS;
    }

    /**
     * @param string $migration_filename
     * @return void
     * @throws FailedToUpdateOption
     */
    private function addToExecutedMigrationsListOption(string $migration_filename): void
    {
        if (!isset($this->executed_migrations_list[$this->getNow()])) {
            $this->executed_migrations_list[$this->getNow()] = [];
        }

        $this->executed_migrations_list[$this->getNow()][] = $migration_filename;

        $this->updateExecutedMigrationsListOption();
    }

    /**
     * @param string $migration_filename
     * @return void
     * @throws FailedToFindExecutedMigrationScript
     * @throws FailedToUpdateOption
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

    /**
     * @return void
     * @throws FailedToUpdateOption
     */
    private function updateExecutedMigrationsListOption(): void
    {
        Option::update(self::MIGRATIONS_WP_OPTION_NAME, $this->executed_migrations_list);
    }

    /**
     * @return array<string, string>
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    private function getMigrationsPathsProvided(): array
    {
        return $this->migrations_paths_provided ??
            $this->migrations_paths_provided = Bootstrapper::bootIntoMigrationCommand();
    }
}
