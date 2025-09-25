<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessInstall\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToResolveWpConfigChmodException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to resolve WP config chmod.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
