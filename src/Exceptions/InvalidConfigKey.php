<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class InvalidConfigKey extends Exception
{
    public function __construct(string $keys_as_string, Throwable $previous = null)
    {
        parent::__construct(
            "Tried to get key '$keys_as_string' from configuration file.",
            0,
            $previous
        );
    }
}
