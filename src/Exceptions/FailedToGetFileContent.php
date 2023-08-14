<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class FailedToGetFileContent extends Exception
{
    private string $filepath;

    public function __construct(string $filepath, Throwable $previous = null)
    {
        $this->filepath = $filepath;

        parent::__construct("Failed to get contents from file at $this->filepath", 0, $previous);
    }
}
