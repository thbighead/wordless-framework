<?php

namespace Wordless\Application\Commands\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Enums\ExceptionCode;

class DotEnvNotSetException extends ErrorException
{
    public function __construct($message = '.env not set.', ?Throwable $previous = null)
    {
        parent::__construct($message, ExceptionCode::development_error->value, previous: $previous);
    }
}
