<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class InvalidRestApiMultipleConfigKey extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Are set "ALLOW" and "DISALLOW" array keys, but only one is accept on rest-api.routes',
            0,
            $previous
        );
    }
}
