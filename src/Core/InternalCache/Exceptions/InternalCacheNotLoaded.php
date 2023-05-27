<?php

namespace Wordless\Core\InternalCache\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InternalCacheNotLoaded extends ErrorException
{
    public function __construct(private readonly string $key_pathing_string, ?Throwable $previous = null)
    {
        parent::__construct(
            "Trying to retrieve '$this->key_pathing_string' key from internal caches before it's loaded (call InternalCache::load()).",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
