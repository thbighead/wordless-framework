<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessInstall\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToRegisterSchedulesException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to register schedules.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
