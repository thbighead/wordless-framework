<?php declare(strict_types=1);

namespace Wordless\Core\InternalCache\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToLoadCachedValues extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not load cached values.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
