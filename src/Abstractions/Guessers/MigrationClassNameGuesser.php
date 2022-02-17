<?php

namespace Wordless\Abstractions\Guessers;

use Wordless\Helpers\Str;

class MigrationClassNameGuesser extends BaseGuesser
{
    /**
     * How many chars prefix the filename
     * @const int
     */
    private const DATE_FORMAT_PREFIX_CHAR_SIZE = 17;
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

    protected function guessValue()
    {
        $script_filename_without_date_prefix_and_extension = substr(
            $this->migration_filename,
            self::DATE_FORMAT_PREFIX_CHAR_SIZE + 1,
            -4
        );

        return 'App\Migrations\\' . Str::studlyCase($script_filename_without_date_prefix_and_extension);
    }
}