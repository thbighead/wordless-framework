<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class PathNotFoundException extends Exception
{
    private string $path;

    /**
     * PathNotFoundException constructor.
     *
     * @param string $path
     * @param Throwable|null $previous
     */
    public function __construct(string $path, Throwable $previous = null)
    {
        $this->path = $path;

        parent::__construct("'$this->path' not found.", 1, $previous);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
