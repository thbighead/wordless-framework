<?php

namespace Wordless\Exception;

use Exception;
use Throwable;

class FailedToGetControllerPathFromCachedData extends Exception
{
    public function __construct(array $controller_cached_data, Throwable $previous = null)
    {
        parent::__construct(
            'Failed to find path key into the following cached data: '
            . var_export($controller_cached_data, true),
            0,
            $previous
        );
    }
}