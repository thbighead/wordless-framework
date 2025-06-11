<?php declare(strict_types=1);

namespace Wordless\Core\InternalCache\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCleanInternalCaches extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'An error occurred while cleaning internal cache files',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
