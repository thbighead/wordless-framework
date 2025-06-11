<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToRunWpCliCommand extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to run WP CLI command.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
