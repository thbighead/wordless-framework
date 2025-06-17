<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Schedules\ListSchedules\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToRunSchedulesListCommand extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to run schedules list command.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
