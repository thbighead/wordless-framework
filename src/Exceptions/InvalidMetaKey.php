<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class InvalidMetaKey extends Exception
{
    public function __construct(string $invalid_meta_key, Throwable $previous = null)
    {
        parent::__construct("The meta key '$invalid_meta_key' is invalid.", 0, $previous);
    }
}
