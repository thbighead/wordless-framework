<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http\Security\Csp\Exceptions;

use ErrorException;
use Exception;
use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToSendCspHeaders extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to send CSP headers.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
