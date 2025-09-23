<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToResolveFinishedChunk extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct($message, ExceptionCode::intentional_interrupt->value, $previous);
    }
}
