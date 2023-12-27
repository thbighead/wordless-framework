<?php

namespace Wordless\Application\Commands\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class CliReturnedNonZero extends ErrorException
{
    public function __construct(
        public readonly string $full_command,
        public readonly int    $script_result_code,
        public readonly string $script_result_output,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "Running $this->full_command returned non-zero ($this->script_result_code)",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
