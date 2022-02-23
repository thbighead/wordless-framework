<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class WpCliCommandReturnedNonZero extends Exception
{
    public function __construct(string $command, int $return_var, Throwable $previous = null)
    {
        parent::__construct(
            "Command \"$command\" returned a non-zero value: $return_var",
            0,
            $previous
        );
    }
}