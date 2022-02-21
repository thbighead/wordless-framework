<?php

namespace Wordless\Abstractions\Guessers;

use Wordless\Abstractions\Migrations\Script;
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

    protected function guessValue(): string
    {
        $script_filename_without_date_prefix_and_extension = substr(
            $this->migration_filename,
            $this->calculateMigrationDateFormatPrefixCharSize() + 1,
            -4
        );

        return 'App\Migrations\\' . Str::studlyCase($script_filename_without_date_prefix_and_extension);
    }

    private function calculateMigrationDateFormatPrefixCharSize(): int
    {
        return strlen(Script::FILENAME_DATE_FORMAT);
    }
}