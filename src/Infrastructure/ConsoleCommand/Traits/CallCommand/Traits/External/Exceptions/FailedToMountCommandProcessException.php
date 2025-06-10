<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\External\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToMountCommandProcessException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to mount command process.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
