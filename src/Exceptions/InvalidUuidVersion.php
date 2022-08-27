<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class InvalidUuidVersion extends Exception
{
    public function __construct(int $invalid_version, Throwable $previous = null)
    {
        parent::__construct("$invalid_version isn't a valid uuid version.", 0, $previous);
    }
}
