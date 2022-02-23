<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class FailedToFindMigrationScript extends Exception
{
    private string $migration_filename;

    public function __construct(string $migration_filename, Throwable $previous = null)
    {
        $this->migration_filename = $migration_filename;

        parent::__construct(
            "Couldn't find the following Migration script file class: $migration_filename.",
            0,
            $previous
        );
    }

    public function getMigrationFilename(): string
    {
        return $this->migration_filename;
    }
}