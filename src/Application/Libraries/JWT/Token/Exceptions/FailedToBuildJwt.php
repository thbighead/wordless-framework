<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\JWT\Token\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Application\Libraries\JWT\Enums\CryptoAlgorithm;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToBuildJwt extends RuntimeException
{
    public function __construct(
        public readonly array            $payload,
        public readonly ?CryptoAlgorithm $crypto_strategy,
        ?Throwable                       $previous = null
    )
    {
        parent::__construct(
            'Could not build a JWT with the given payload and crypto algorithm strategy.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
