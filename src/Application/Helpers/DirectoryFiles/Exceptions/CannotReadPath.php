<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class CannotReadPath extends RuntimeException
{
    public function __construct(readonly public string $path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to read path '$this->path'.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
