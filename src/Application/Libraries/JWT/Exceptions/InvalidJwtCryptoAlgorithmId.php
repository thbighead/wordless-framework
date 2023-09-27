<?php

namespace Wordless\Application\Libraries\JWT\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidJwtCryptoAlgorithmId extends ErrorException
{
    public function __construct(private readonly ?string $crypto_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "The following JWT crypto identifier is not valid: $this->crypto_key",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }

    public function getCryptoKey(): ?string
    {
        return $this->crypto_key;
    }
}
