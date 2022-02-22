<?php

namespace Wordless\Abstractions\Guessers;

use Wordless\Abstractions\Migrations\Script;
use Wordless\Exception\InvalidDateFormat;
use Wordless\Helpers\Str;

class MigrationClassNameGuesser extends BaseGuesser
{
    private string $migration_filename;

    public function __construct(string $migration_filename = '')
    {
        $this->migration_filename = $migration_filename;
    }

    public function setMigrationFilename(string $migration_filename): MigrationClassNameGuesser
    {
        $this->migration_filename = $migration_filename;

        return $this;
    }

    /**
     * @return string
     * @throws InvalidDateFormat
     */
    protected function guessValue(): string
    {
        $script_filename_without_date_prefix_and_extension = substr(
            $this->migration_filename,
            $this->calculateMigrationDateFormatPrefixCharSize(),
            -4
        );

        return Str::studlyCase($script_filename_without_date_prefix_and_extension);
    }

    /**
     * @return int
     * @throws InvalidDateFormat
     */
    private function calculateMigrationDateFormatPrefixCharSize(): int
    {
        if (($date_formatted = date(Script::FILENAME_DATE_FORMAT)) === false) {
            throw new InvalidDateFormat(Script::FILENAME_DATE_FORMAT);
        }

        return strlen($date_formatted);
    }
}