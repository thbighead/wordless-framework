<?php

namespace Wordless\Application\Commands\WordlessInstall\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCreateWordlessPluginFromStubException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to create Wordless plugin from stubs.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
