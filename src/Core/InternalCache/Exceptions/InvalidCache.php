<?php

namespace Wordless\Core\InternalCache\Exceptions;

use DomainException;
use Throwable;
use Wordless\Enums\ExceptionCode;

class InvalidCache extends DomainException
{
    public function __construct(
        private readonly string $cache_file_path,
        private readonly string $reason,
        ?Throwable              $previous = null
    )
    {
        parent::__construct(
            "Failed to read cache at $this->cache_file_path. $this->reason",
            ExceptionCode::development_error->value,
            $previous
        );
    }

    public function getCacheFilePath(): string
    {
        return $this->cache_file_path;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
