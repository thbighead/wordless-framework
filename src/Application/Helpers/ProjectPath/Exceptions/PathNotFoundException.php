<?php

namespace Wordless\Application\Helpers\ProjectPath\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class PathNotFoundException extends ErrorException
{
    public function __construct(private readonly string $path, ?Throwable $previous = null)
    {
        parent::__construct(
            "'$this->path' not found.",
            ExceptionCode::logic_control->value,
            previous: $previous
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
