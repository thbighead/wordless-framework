<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCreateSymlink extends ErrorException
{
    public function __construct(
        public readonly string $link_relative_path,
        public readonly string $target_relative_path,
        public readonly string $from_absolute_path,
        Throwable              $previous = null
    )
    {
        parent::__construct(
            "Failed to create $this->link_relative_path symlink pointing to $this->target_relative_path from $this->from_absolute_path",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
