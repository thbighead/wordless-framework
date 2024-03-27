<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToPutFileContent extends ErrorException
{
    public function __construct(
        public readonly string $filepath,
        public readonly string $intended_content,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "Failed to put contents in file at $this->filepath",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
