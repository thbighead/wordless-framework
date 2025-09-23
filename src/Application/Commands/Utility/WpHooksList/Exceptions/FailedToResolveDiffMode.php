<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\WpHooksList\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToResolveDiffMode extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not resolve diff mode.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
