<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\External\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class CallExternalCommandException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to call external command.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
