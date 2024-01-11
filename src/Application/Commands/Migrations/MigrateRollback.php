<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Migrations\Migrate\Exceptions\FailedToFindMigrationScript;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
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
            new OptionDTO(
                self::NUMBER_OF_CHUNKS_OPTION,
                'How many chunks you want to rollback. Default is 1.',
                mode: OptionMode::optional_value
            ),
        ];
    }

    /**
     * @return int
     * @throws PathNotFoundException
     * @throws FailedToFindMigrationScript
     * @throws InvalidDirectory
     */
    protected function runIt(): int
    {
        $executed_migrations_list = $this->getExecutedMigrationsChunksList();

        if (empty($executed_migrations_list)) {
            $this->writelnInfo('Nothing to rollback.');
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

    private function getNumberOfChunks(): int
    {
        if (isset($this->number_of_chunks)) {
            return $this->number_of_chunks;
        }

        $chunks_input_option_value = $this->input->getOption(self::NUMBER_OF_CHUNKS_OPTION);

        if (strtolower($chunks_input_option_value) === self::ALL_CHUNKS_VALUE) {
            return PHP_INT_MAX;
        }

        return $this->number_of_chunks = max((int)$chunks_input_option_value, 1);
    }
}
