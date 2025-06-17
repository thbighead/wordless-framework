<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations\Migrate\Traits\ForceMode\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToResolveForceMode extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to resolve force mode.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
