<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class QueryAlreadySet extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct(
            'Query Builder should set query only once. Can\'t set query again.',
            0,
            $previous
        );
    }
}
