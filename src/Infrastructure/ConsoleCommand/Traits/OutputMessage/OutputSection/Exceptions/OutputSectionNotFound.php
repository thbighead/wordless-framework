<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\OutputSection\Exceptions;

use LogicException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class OutputSectionNotFound extends LogicException
{
    public function __construct(public readonly int $invalid_position, ?Throwable $previous = null)
    {
        parent::__construct(
            "There's no output section initialized at $this->invalid_position position.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
