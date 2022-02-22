<?php

namespace Wordless\Exception;

use Throwable;

class FailedToFindExecutedMigrationScript extends FailedToFindMigrationScript
{
    public function __construct(string $executed_migration_filename, Throwable $previous = null)
    {
        parent::__construct($executed_migration_filename, $previous);
    }
}