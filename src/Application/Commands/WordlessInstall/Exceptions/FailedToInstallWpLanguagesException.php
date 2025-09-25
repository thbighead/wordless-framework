<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessInstall\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToInstallWpLanguagesException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to install WP languages.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
