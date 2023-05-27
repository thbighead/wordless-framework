<?php

namespace Wordless\Application\Commands\Migrate\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToFindMigrationScript extends DomainException
{
    public function __construct(private readonly string $migration_filename, ?Throwable $previous = null)
    {
        parent::__construct(
            "Couldn't find the following Migration script file class: $this->migration_filename.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }

    public function getMigrationFilename(): string
    {
        return $this->migration_filename;
    }
}
