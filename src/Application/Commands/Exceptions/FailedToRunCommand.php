<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToRunCommand extends RuntimeException
{
    public function __construct(string $command, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to run '$command' command.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
