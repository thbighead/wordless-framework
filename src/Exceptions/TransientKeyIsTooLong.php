<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class TransientKeyIsTooLong extends Exception
{
    public function __construct(string $invalid_key, Throwable $previous = null)
    {
        parent::__construct(
            "The value '$invalid_key' is too long to be a transient key. Check https://developer.wordpress.org/reference/functions/get_transient/#more-information for more info.",
            0,
            $previous
        );
    }
}
