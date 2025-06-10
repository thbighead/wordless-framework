<?php

namespace Wordless\Application\Cachers\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToMountCacheArray extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed on try mount cache array.',
            ExceptionCode::development_error,
            $previous
        );
    }
}
