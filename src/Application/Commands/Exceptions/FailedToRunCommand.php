<?php

namespace Wordless\Application\Commands\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToRunCommand extends RuntimeException
{
    public function __construct(string $command, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed on try run $command command.",
            ExceptionCode::development_error,
            $previous
        );
    }
}
