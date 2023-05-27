<?php

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidDirectory extends ErrorException
{
    public function __construct(private readonly string $supposed_directory_path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to use '$this->supposed_directory_path' as a directory",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
