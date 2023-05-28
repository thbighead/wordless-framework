<?php

namespace Wordless\Application\Guessers;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Guessers;
use Wordless\Infrastructure\Migration\Script;

class MigrationClassNameGuesser extends Guesser
{
    private string $migration_filename;
    private int $migration_date_format_prefix_char_size;

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
            $this->getMigrationDateFormatPrefixCharSize(),
            -strlen('.php')
        );

        return Str::pascalCase($script_filename_without_date_prefix_and_extension);
    }

    private function getMigrationDateFormatPrefixCharSize(): int
    {
        if (isset($this->migration_date_format_prefix_char_size)) {
            return $this->migration_date_format_prefix_char_size;
        }

        return $this->migration_date_format_prefix_char_size = strlen(date(Script::FILENAME_DATE_FORMAT));
    }
}
