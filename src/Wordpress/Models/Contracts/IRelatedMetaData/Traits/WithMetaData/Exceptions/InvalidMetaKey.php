<?php

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Exceptions;

use Exception;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidMetaKey extends Exception
{
    public function __construct(private readonly string $invalid_meta_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "The meta key '$this->invalid_meta_key' is invalid.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
