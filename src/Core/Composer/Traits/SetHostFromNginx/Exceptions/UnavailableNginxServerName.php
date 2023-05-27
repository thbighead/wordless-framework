<?php

namespace Wordless\Core\Composer\Traits\SetHostFromNginx\Exceptions;

use Exception;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class UnavailableNginxServerName extends Exception
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, ExceptionCode::caught_internally->value, $previous);
    }
}
