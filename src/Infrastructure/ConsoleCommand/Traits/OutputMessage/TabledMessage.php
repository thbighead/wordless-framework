<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;

use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\SyntaxError;
use League\Csv\UnavailableStream;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;

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
     * @param string $tsv_reference
     * @param string|null $table_title
     * @return Table|null
     * @throws Exception
     * @throws SyntaxError
     */
    protected function mountTableFromTsv(string $tsv_reference, ?string $table_title = null): ?Table
    {
        if (empty($tsv_reference)) {
            return null;
        }

        try {
            $tsv = Reader::createFromPath($tsv_reference);
        } catch (UnavailableStream) {
            $tsv = Reader::createFromString($tsv_reference);
        }

        $tsv->setHeaderOffset(0)->setDelimiter("\t");

        $csv_rows = [];

        foreach ($tsv->getRecords() as $csv_row) {
            $csv_rows[] = $csv_row;
        }

        $table = $this->mountTable();

        if (!empty($table_title)) {
            $table->setHeaderTitle($table_title);
        }

        return $table->setHeaders($tsv->getHeader())
            ->setRows($csv_rows);
    }

    /**
     * @param string $csv_reference
     * @param string|null $table_title
     * @param bool $without_decoration
     * @return void
     * @throws Exception
     * @throws SyntaxError
     */
    protected function writeTableFromCsv(
        string $csv_reference,
        ?string $table_title = null,
        bool $without_decoration = false
    ): void
    {
        $table = $this->mountTableFromCsv($csv_reference, $table_title);

        if ($without_decoration) {
            $table->setStyle((new TableStyle)
                ->setCellHeaderFormat('%s')
                ->setHeaderTitleFormat('%s'));
        }

        $table->render();
    }

    /**
     * @param string $tsv_reference
     * @param string|null $table_title
     * @param bool $without_decoration
     * @return void
     * @throws Exception
     * @throws SyntaxError
     */
    protected function writeTableFromTsv(
        string  $tsv_reference,
        ?string $table_title = null,
        bool    $without_decoration = false
    ): void
    {
        $table = $this->mountTableFromTsv($tsv_reference, $table_title);

        if ($without_decoration) {
            $table->setStyle((new TableStyle)
                ->setCellHeaderFormat('%s')
                ->setHeaderTitleFormat('%s'));
        }

        $table->render();
    }
}
