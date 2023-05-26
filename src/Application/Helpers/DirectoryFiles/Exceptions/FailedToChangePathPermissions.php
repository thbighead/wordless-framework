<?php

namespace Wordless\Application\Helpers\DirestoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Enums\ExceptionCode;

class FailedToChangePathPermissions extends ErrorException
{
    public function __construct(
        private readonly string $path,
        private readonly int $permissions,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Failed to change $this->path permissions to $this->permissions",
            ExceptionCode::logic_control->value,
            previous: $previous
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getPermissions(): int
    {
        return $this->permissions;
    }
}
