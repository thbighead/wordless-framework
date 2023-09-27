<?php

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;

class FailedToPutFileContent extends ErrorException
{
    private string $filepath;

    public function __construct(string $filepath, Throwable $previous = null)
    {
        $this->filepath = $filepath;

        parent::__construct("Failed to put contents in file at $this->filepath", 0, $previous);
    }

    public function getFilepath(): string
    {
        return $this->filepath;
    }
}
