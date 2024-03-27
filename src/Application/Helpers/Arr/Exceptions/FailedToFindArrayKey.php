<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Arr\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToFindArrayKey extends ErrorException
{
    public function __construct(
        public readonly array  $array,
        public readonly string $full_key_string,
        public readonly string $partial_key_which_failed,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "Failed to retrieve '$this->full_key_string' key from an array at '$this->partial_key_which_failed'.",
            ExceptionCode::logic_control->value,
            previous: $previous
        );
    }
}
