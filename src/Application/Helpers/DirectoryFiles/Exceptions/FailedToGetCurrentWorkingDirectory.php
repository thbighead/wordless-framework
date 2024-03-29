<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGetCurrentWorkingDirectory extends ErrorException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to get current working directory. (getcwd() returned false)',
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
