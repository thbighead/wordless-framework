<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToLoadBootstrapper extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to load Bootstrapper.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
