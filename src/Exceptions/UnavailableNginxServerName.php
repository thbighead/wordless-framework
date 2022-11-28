<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class UnavailableNginxServerName extends Exception
{
     public function __construct(string $message, Throwable $previous = null)
     {
         parent::__construct($message, 0, $previous);
     }
}
