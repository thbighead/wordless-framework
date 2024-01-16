<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations\Migrate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Migrations\Migrate;
use Wordless\Application\Commands\Migrations\Migrate\Exceptions\FailedToFindExecutedMigrationScript;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\InvalidMigrationFilename;
use Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions\MigrationFileNotFound;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

class MigrateRollback extends Migrate
{
    final public const COMMAND_NAME = 'migrate:rollback';
    private const NUMBER_OF_CHUNKS_OPTION = 'chunks';
    private const ALL_CHUNKS_VALUE = 'all';

    private int $number_of_chunks;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Execute the last migration chunk down method to undo its changes.';
    }

    protected function help(): string
    {
        return 'Undo all changes made by migrations at the last chunks you define (default is just the last one).';
    }

    protected function options(): array
    {
        return [
            OptionDTO::make(
                self::NUMBER_OF_CHUNKS_OPTION,
                'How many chunks you want to rollback. Default is 1.',
                mode: OptionMode::optional_value,
                default: self::ALL_CHUNKS_VALUE
            ),
        ];
    }

    /**
     * @return int
     * @throws FailedToFindExecutedMigrationScript
     * @throws FailedToUpdateOption
     * @throws InvalidArgumentException
     * @throws InvalidConfigKey
     * @throws InvalidMigrationFilename
     * @throws InvalidProviderClass
     * @throws MigrationFileNotFound
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $executed_migrations_list = array_values($this->getExecutedMigrationsChunksList());

        if (empty($executed_migrations_list)) {
            $this->writelnInfo('Nothing to rollback.');

            return Command::SUCCESS;
        }

        if (($executed_migrations_list_size = count($executed_migrations_list)) < $this->getNumberOfChunks()) {
            $this->number_of_chunks = $executed_migrations_list_size;
        }

        for ($i = 0; $i < $this->getNumberOfChunks(); $i++) {
            $executed_migrations_chunk = $executed_migrations_list[$i];

            for ($j = count($executed_migrations_chunk) - 1; $j >= 0; $j--) {
                $executed_migration_filename = $executed_migrations_chunk[$j];

                $this->executeMigrationScriptFile($executed_migration_filename, false);
            }
        }

        return Command::SUCCESS;
    }

    /**
     * @return int
     * @throws InvalidArgumentException
     */
    private function getNumberOfChunks(): int
    {
        if (isset($this->number_of_chunks)) {
            return $this->number_of_chunks;
        }

        $chunks_input_option_value = (string)$this->input->getOption(self::NUMBER_OF_CHUNKS_OPTION);

        if (strtolower($chunks_input_option_value) === self::ALL_CHUNKS_VALUE) {
            return PHP_INT_MAX;
        }

        return $this->number_of_chunks = max((int)$chunks_input_option_value, 1);
    }
}
