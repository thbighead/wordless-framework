<?php declare(strict_types=1);

namespace Wordless\Application\Commands\GeneratePublicWordpressSymbolicLinks\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToEnsureSymlinkDirectoryHierarchyAtPublic extends RuntimeException
{
    public function __construct(public readonly string $link_absolute_parent_path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Could not ensure directory at $this->link_absolute_parent_path",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
