<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class TransientKeyNotFound extends Exception
{
    public function __construct(string $key, Throwable $previous = null)
    {
        parent::__construct("Failed to find any data cached by key '$key'.", 0, $previous);
    }
}