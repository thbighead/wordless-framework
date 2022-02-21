<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Wordless\Abstractions\Guessers\MigrationClassNameGuesser;
use Wordless\Abstractions\Migrations\Script;
use Wordless\Adapters\WordlessCommand;

class MigrateRollback extends WordlessCommand
{
    protected static $defaultName = 'migrate:rollback';

    private const NUMBER_OF_CHUNKS_OPTION = 'chunks';
    private const ALL_CHUNKS_VALUE = 'all';

    private int $number_of_chunks;

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
            [
                self::OPTION_NAME_FIELD => self::NUMBER_OF_CHUNKS_OPTION,
                self::OPTION_MODE_FIELD => InputOption::VALUE_OPTIONAL,
                self::OPTION_DESCRIPTION_FIELD => 'How many chunks you want to rollback. Default is 1.',
            ],
        ];
    }

    protected function runIt(): int
    {
        $executed_migrations_list = $this->getOrderedExecutedMigrationsChunksList();

        if (($executed_migrations_list_size = count($executed_migrations_list)) < $this->getNumberOfChunks()) {
            $this->number_of_chunks = $executed_migrations_list_size;
        }

        for ($i = 0; $i < $this->getNumberOfChunks(); $i++) {
            $executed_migrations_chunk = $executed_migrations_list[$i];

            foreach (array_reverse($executed_migrations_chunk) as $executed_migration_filename) {
                include_once $executed_migration_filename;
                $executed_migration_namespaced_class = $this
                    ->guessMigrationClassNameFromFileName($executed_migration_filename);
                /** @var Script $migrationObject */
                $migrationObject = new $executed_migration_namespaced_class;
                $migrationObject->down();
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

    private function getOrderedExecutedMigrationsChunksList(): array
    {
        if (isset($this->executed_migrations_list)) {
            return $this->executed_migrations_list;
        }

        return $this->executed_migrations_list = array_reverse(get_option(Migrate::MIGRATIONS_WP_OPTION_NAME, []));
    }

    /**
     * @param string $migration_filename
     * @return string
     */
    private function guessMigrationClassNameFromFileName(string $migration_filename): string
    {
        return (new MigrationClassNameGuesser($migration_filename))->getValue();
    }
}
