<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;

use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\SyntaxError;
use League\Csv\UnavailableStream;
use Symfony\Component\Console\Helper\Table;

trait TabledMessage
{
    /**
     * https://symfony.com/doc/current/components/console/helpers/table.html
     *
     * @return Table
     */
    protected function mountTable(): Table
    {
        return new Table($this->output);
    }

    /**
     * @param string $csv_reference
     * @param string|null $table_title
     * @return Table|null
     * @throws Exception
     * @throws SyntaxError
     */
    protected function mountTableFromCsv(string $csv_reference, ?string $table_title = null): ?Table
    {
        if (empty($csv_reference)) {
            return null;
        }

        try {
            $csv = Reader::createFromPath($csv_reference);
        } catch (UnavailableStream) {
            $csv = Reader::createFromString($csv_reference);
        }

        $csv->setHeaderOffset(0);

        $csv_rows = [];

        foreach ($csv->getRecords() as $csv_row) {
            $csv_rows[] = $csv_row;
        }

        $table = $this->mountTable();

        if (!empty($table_title)) {
            $table->setHeaderTitle($table_title);
        }

        return $table->setHeaders($csv->getHeader())
            ->setRows($csv_rows);
    }

    /**
     * @param string $csv_reference
     * @param string|null $table_title
     * @return void
     * @throws Exception
     * @throws SyntaxError
     */
    protected function writeTableFromCsv(string $csv_reference, ?string $table_title = null): void
    {
        $this->mountTableFromCsv($csv_reference, $table_title)->render();
    }
}
