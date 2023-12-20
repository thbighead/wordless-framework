<?php

namespace Wordless\Application\Helpers\DataCache\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class TransientKeyNotFound extends InvalidArgumentException
{
    public function __construct(private readonly string $key, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to find any data cached by key '$this->key'.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
