<?php

namespace Wordless\Exception;

use Exception;
use Throwable;

class DotEnvNotSetException extends Exception
{
    public function __construct($message = ".env not set.", Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}