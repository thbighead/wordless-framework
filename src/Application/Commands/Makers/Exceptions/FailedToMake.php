<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToMake extends RuntimeException
{
    public function __construct(public readonly string $what, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to make $this->what through command.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
