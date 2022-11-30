<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class InvalidJwtCryptoAlgorithmId extends Exception
{
    public function __construct(string $crypto_key, Throwable $previous = null)
    {
        parent::__construct(
            "The following JWT crypto identifier is not valid: $crypto_key",
            0,
            $previous
        );
    }
}
