<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\TabledMessage\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToMountTableFromJsonException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to mount table from json.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
