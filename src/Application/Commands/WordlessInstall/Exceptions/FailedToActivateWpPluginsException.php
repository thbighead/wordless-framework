<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessInstall\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToActivateWpPluginsException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to activate WP plugins.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
