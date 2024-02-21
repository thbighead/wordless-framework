<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidChunkValue extends DomainException
{
    public function __construct(public readonly string $invalid_value, ?Throwable $previous = null)
    {
        parent::__construct(
            "The value '$this->invalid_value' results in a non-integer or less than or equals to zero, which is invalid",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
