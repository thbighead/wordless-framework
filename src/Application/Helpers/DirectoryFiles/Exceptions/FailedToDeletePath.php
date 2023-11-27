<?php

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToDeletePath extends ErrorException
{
    public function __construct(private readonly string $path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Couldn't delete $this->path.",
            ExceptionCode::logic_control->value,
            previous: $previous
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
