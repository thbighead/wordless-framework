<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class TryingToBuildEmptySubQuery extends Exception
{
    public function __construct(string $subQueryClass, Throwable $previous = null)
    {
        parent::__construct(
            "$subQueryClass can't be built.",
            0,
            $previous
        );
    }
}
