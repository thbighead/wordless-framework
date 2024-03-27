<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations\Migrate\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToFindMigrationScript extends DomainException
{
    public function __construct(public readonly string $migration_filename, ?Throwable $previous = null)
    {
        parent::__construct(
            "Couldn't find the following Migration script file class: $this->migration_filename.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
