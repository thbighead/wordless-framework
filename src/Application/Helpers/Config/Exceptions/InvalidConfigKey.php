<?php

namespace Wordless\Application\Helpers\Config\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidConfigKey extends DomainException
{
    public function __construct(private readonly string $keys_as_string, ?Throwable $previous = null)
    {
        parent::__construct(
            "Tried to get key '$this->keys_as_string' from configuration file.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }

    public function getKeysAsString(): string
    {
        return $this->keys_as_string;
    }
}
