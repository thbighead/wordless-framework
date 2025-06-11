<?php

namespace Wordless\Application\Commands\WordlessInstall\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToSwitchToMaintenanceModeException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to switch to maintenance mode.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
