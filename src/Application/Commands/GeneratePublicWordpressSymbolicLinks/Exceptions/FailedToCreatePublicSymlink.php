<?php declare(strict_types=1);

namespace Wordless\Application\Commands\GeneratePublicWordpressSymbolicLinks\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCreatePublicSymlink extends RuntimeException
{
    public function __construct(
        public readonly string $link_relative_path,
        public readonly string $target_relative_path,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "Failed to create symlink $this->link_relative_path targeting $this->target_relative_path from public directory.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
