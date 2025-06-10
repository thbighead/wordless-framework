<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;

use JsonException;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\SyntaxError;
use League\Csv\UnavailableStream;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\TabledMessage\Exceptions\FailedToMountTableFromCsvException;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\TabledMessage\Exceptions\FailedToMountTableFromJsonException;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\TabledMessage\Exceptions\FailedToMountTableFromTsvException;

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
     * @throws FailedToMountTableFromCsvException
     */
    protected function mountTableFromCsv(string $csv_reference, ?string $table_title = null): ?Table
    {
        if (empty($csv_reference)) {
            return null;
        }

        try {
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
        } catch (Exception|SyntaxError $exception) {
            throw new FailedToMountTableFromCsvException($exception);
        }
    }

    /**
     * @param string $json_reference
     * @param string|null $root_reference
     * @param string|null $table_title
     * @return Table|null
     * @throws FailedToMountTableFromJsonException
     */
    protected function mountTableFromJson(
        string  $json_reference,
        ?string $root_reference = null,
        ?string $table_title = null
    ): ?Table
    {
        if (empty($json_reference)) {
            return null;
        }

        try {
            try {
                $json_content = json_decode(
                    is_file($json_reference) ? DirectoryFiles::getFileContent($json_reference) : $json_reference,
                    true,
                    flags: JSON_THROW_ON_ERROR
                );
            } catch (JsonException) {
                return null;
            }

            if (!is_array($json_content)) {
                return null;
            }

            if (!empty($root_reference)) {
                $json_content = Arr::get($json_content, $root_reference, []);
            }

            if (empty($json_content)) {
                return null;
            }

            $table = $this->mountTable();

            if (!empty($table_title)) {
                $table->setHeaderTitle($table_title);
            }

            return $table->setHeaders(array_keys($json_content[0]))
                ->setRows($json_content);
        } catch (FailedToGetFileContent|FailedToParseArrayKey|PathNotFoundException $exception) {
            throw new FailedToMountTableFromJsonException($exception);
        }
    }

    /**
     * @param string $tsv_reference
     * @param string|null $table_title
     * @return Table|null
     * @throws FailedToMountTableFromTsvException
     */
    protected function mountTableFromTsv(string $tsv_reference, ?string $table_title = null): ?Table
    {
        if (empty($tsv_reference)) {
            return null;
        }

        try {
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
        } catch (InvalidArgument|SyntaxError|Exception $exception) {
            throw new FailedToMountTableFromTsvException($exception);
        }
    }

    /**
     * @param string $csv_reference
     * @param string|null $table_title
     * @param bool $without_decoration
     * @return void
     * @throws FailedToMountTableFromCsvException
     */
    protected function writeTableFromCsv(
        string  $csv_reference,
        ?string $table_title = null,
        bool    $without_decoration = false
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
     * @param string $json_reference
     * @param string|null $root_reference
     * @param string|null $table_title
     * @param bool $without_decoration
     * @return void
     * @throws FailedToMountTableFromJsonException
     */
    protected function writeTableFromJson(
        string  $json_reference,
        ?string $root_reference = null,
        ?string $table_title = null,
        bool    $without_decoration = false
    ): void
    {
        $table = $this->mountTableFromJson($json_reference, $root_reference, $table_title);

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
     * @throws FailedToMountTableFromTsvException
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
