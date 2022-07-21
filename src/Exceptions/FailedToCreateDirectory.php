<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class FailedToCreateDirectory extends Exception
{
    private string $path;

    public function __construct(string $path, Throwable $previous = null)
    {
        $this->path = $path;

        parent::__construct("Failed to create directory $this->path", 0, $previous);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}