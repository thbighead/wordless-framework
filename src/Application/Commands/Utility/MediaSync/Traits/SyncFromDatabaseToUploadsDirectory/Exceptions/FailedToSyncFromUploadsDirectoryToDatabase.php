<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToSyncFromUploadsDirectoryToDatabase extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to synchronise uploads directory into database.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
