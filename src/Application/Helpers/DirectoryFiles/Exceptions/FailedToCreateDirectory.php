<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCreateDirectory extends ErrorException
{
    public function __construct(public readonly string $path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to create directory $this->path",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
