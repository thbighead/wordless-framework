<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCallWpCliCommand extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to call WP CLI command.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
