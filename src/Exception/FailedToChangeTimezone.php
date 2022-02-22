<?php

namespace Wordless\Exception;

use Exception;
use Throwable;

class FailedToChangeTimezone extends Exception
{
    public function __construct(string $invalid_timezone, Throwable $previous = null)
    {
        parent::__construct("Failed to change timezone to '$invalid_timezone'", 0, $previous);
    }
}