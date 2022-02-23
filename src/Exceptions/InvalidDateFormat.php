<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class InvalidDateFormat extends Exception
{
    public function __construct(string $invalid_date_format, Throwable $previous = null)
    {
        parent::__construct("The format '$invalid_date_format' is invalid for dates.", 0, $previous);
    }
}