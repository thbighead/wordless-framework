<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToResolveNoPermissionsMode extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not resolve "no permissions mode" to auto register or not permissions to admin role.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
