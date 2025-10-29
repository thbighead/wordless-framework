<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Exceptions\Traits\SettablePrevious;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGetFileContent extends ErrorException
{
    use SettablePrevious;

    public function __construct(private readonly string $filepath, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to get content from file at $this->filepath",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
