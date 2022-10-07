<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class InternalCacheNotLoaded extends Exception
{
    public function __construct(string $key_pathing_string, Throwable $previous = null)
    {
        parent::__construct(
            "Trying to retrieve '$key_pathing_string' key from internal caches before it's loaded (call InternalCache::load()).",
            0,
            $previous
        );
    }
}
