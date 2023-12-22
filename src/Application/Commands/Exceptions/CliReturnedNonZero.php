<?php

namespace Wordless\Application\Commands\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class CliReturnedNonZero extends ErrorException
{
    public function __construct(
        public readonly string $command,
        public readonly int $command_result,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Running $this->command returned non-zero ($this->command_result)",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
