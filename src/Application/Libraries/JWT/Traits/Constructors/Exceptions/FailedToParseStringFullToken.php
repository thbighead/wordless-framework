<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\JWT\Traits\Constructors\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToParseStringFullToken extends RuntimeException
{
    public function __construct(public readonly string $full_token, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to parse JWT: $this->full_token",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
