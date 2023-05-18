<?php

namespace Wordless\Application\Helpers\DirestoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Enums\ExceptionCode;

class FailedToCreateDirectory extends ErrorException
{
    public function __construct(private readonly string $path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to create directory $this->path",
            ExceptionCode::logic_control->value,
            previous: $previous
        );
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
