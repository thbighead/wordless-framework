<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToChangePathPermissions extends ErrorException
{
    public function __construct(
        public readonly string $path,
        public readonly int    $permissions,
        ?Throwable              $previous = null
    )
    {
        parent::__construct(
            "Failed to change $this->path permissions to $this->permissions",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
