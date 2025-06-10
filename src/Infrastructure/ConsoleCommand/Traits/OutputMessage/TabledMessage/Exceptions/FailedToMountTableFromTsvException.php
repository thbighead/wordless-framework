<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\TabledMessage\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToMountTableFromTsvException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to mount table from tsv.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
