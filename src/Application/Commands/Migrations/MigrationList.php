<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Option;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class MigrationList extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'migrate:list';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Lists all migrations already executed.';
    }

    protected function help(): string
    {
        return 'Show every migration saved as executed details inside the `options` database table.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws InvalidArgumentException
     */
    protected function runIt(): int
    {
        $executed_migrations_list = Option::get(Migrate::MIGRATIONS_WP_OPTION_NAME, []);
        $outputTable = $this->mountTable();

        $outputTable->setHeaders(['Chunk Datetime', 'Ordered File(s) Executed']);

        $is_first_chunk = true;
        foreach ($executed_migrations_list as $execution_datetime => $migration_chunk) {
            if (!$is_first_chunk) {
                $outputTable->addRow(new TableSeparator);
            }

            $is_first_chunk = false;

            if (($how_many_files_were_executed = count($migration_chunk)) === 1) {
                $outputTable->addRow([$execution_datetime, $migration_chunk[0]]);
                continue;
            }

            $chunk_rows = [];

            foreach ($migration_chunk as $executed_file) {
                $chunk_rows[] = [$executed_file];
            }

            $chunk_rows[0] = Arr::prepend(
                $chunk_rows[0],
                new TableCell($execution_datetime, ['rowspan' => $how_many_files_were_executed])
            );

            $outputTable->addRows($chunk_rows);
        }

        $outputTable->render();

        return Command::SUCCESS;
    }
}
