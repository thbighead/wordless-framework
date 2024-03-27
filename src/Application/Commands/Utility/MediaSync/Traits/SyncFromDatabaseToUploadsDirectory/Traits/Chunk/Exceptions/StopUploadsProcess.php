<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions;

use Exception;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class StopUploadsProcess extends Exception
{
    public function __construct(public readonly int $chunk_number, ?Throwable $previous = null)
    {
        parent::__construct(
            "Process stoped in chunk $this->chunk_number",
            ExceptionCode::caught_internally->value,
            $previous
        );
    }
}
