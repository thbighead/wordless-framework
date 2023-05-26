<?php

namespace Wordless\Application\Helpers\DirestoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Enums\ExceptionCode;

class FailedToGetDirectoryPermissions extends ErrorException
{
    public function __construct(private readonly string $path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to get $this->path permissions value.",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
