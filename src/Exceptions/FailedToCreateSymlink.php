<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class FailedToCreateSymlink extends Exception
{
    public function __construct(string $command, Throwable $previous = null)
    {
        parent::__construct("Failed to create symbolic link using \"$command\"", 0, $previous);
    }
}