<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class InvalidConfigKey extends Exception
{
    public function __construct(string $keys_as_string, Throwable $previous = null)
    {
        parent::__construct(
            "Tried to get key '$keys_as_string' from configutation file.",
            0,
            $previous
        );
    }
}
