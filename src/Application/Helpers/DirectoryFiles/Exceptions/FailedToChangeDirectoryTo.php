<?php

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToChangeDirectoryTo extends ErrorException
{
    public function __construct(private readonly string $path_to_change_to, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to change working directory to $this->path_to_change_to",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }

    public function getPathToChangeTo(): string
    {
        return $this->path_to_change_to;
    }
}
