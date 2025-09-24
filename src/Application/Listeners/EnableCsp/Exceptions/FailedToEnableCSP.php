<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\EnableCsp\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToEnableCSP extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to enable CSP.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
