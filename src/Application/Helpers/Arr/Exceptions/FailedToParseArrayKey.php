<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Arr\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToParseArrayKey extends ErrorException
{
    public function __construct(
        public readonly string $full_key_string,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "Failed to parse '$this->full_key_string'.",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
