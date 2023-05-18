<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class FailedToWriteInFile extends Exception
{
    public function __construct(string $file_pathing, Throwable $previous = null)
    {
        parent::__construct("Failed to write content into $file_pathing", 0, $previous);
    }
}
