<?php

namespace Wordless\Core\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class DotEnvNotSetException extends ErrorException
{
    public function __construct(string $message = '.env not set.', ?Throwable $previous = null)
    {
        parent::__construct($message, ExceptionCode::development_error->value, previous: $previous);
    }
}
