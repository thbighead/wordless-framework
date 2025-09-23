<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations\Migrate\MigrateRollback\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToFilterExecutedMigrationsToRollback extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not filter executed migrations to rollback.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
