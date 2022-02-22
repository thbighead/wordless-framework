<?php

namespace Wordless\Exception;

use Exception;
use Throwable;

class FailedToGetDirectoryPermissions extends Exception
{
    public function __construct(string $path, Throwable $previous = null)
    {
        parent::__construct("Failed to get $path permissions value.", 0, $previous);
    }
}