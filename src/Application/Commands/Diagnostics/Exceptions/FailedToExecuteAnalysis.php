<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Diagnostics\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToExecuteAnalysis extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to get execute analysis.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
