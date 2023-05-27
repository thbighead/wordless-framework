<?php

namespace Wordless\Application\Helpers\DataCache\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Enums\ExceptionCode;

class TransientKeyIsTooLong extends ErrorException
{
    public function __construct(private readonly string $invalid_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "The value '$this->invalid_key' is too long to be a transient key. Check https://developer.wordpress.org/reference/functions/get_transient/#more-information for more info.",
            ExceptionCode::development_error->value,
            previous: $previous
        );
    }

    public function getInvalidKey(): string
    {
        return $this->invalid_key;
    }
}
