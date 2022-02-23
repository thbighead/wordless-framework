<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class FailedToCreateDirectory extends Exception
{
    public function __construct(string $path, Throwable $previous = null)
    {
        parent::__construct("Failed to create directory $path", 0, $previous);
    }
}