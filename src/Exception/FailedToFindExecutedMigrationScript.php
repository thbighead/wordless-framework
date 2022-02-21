<?php

namespace Wordless\Exception;

use Exception;
use Throwable;

class FailedToFindExecutedMigrationScript extends Exception
{
    public function __construct(string $executed_migration_namespaced_class, Throwable $previous = null)
    {
        parent::__construct(
            "Couldn't find the following Migration script file class: $executed_migration_namespaced_class",
            0,
            $previous
        );
    }
}