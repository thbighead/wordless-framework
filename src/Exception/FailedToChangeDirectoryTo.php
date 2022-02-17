<?php

namespace Wordless\Exception;

use Exception;
use Throwable;

class FailedToChangeDirectoryTo extends Exception
{
    public function __construct(string $path_to_change_to, Throwable $previous = null)
    {
        parent::__construct("Failed to change working directory to $path_to_change_to", 0, $previous);
    }
}