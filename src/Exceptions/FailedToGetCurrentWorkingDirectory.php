<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class FailedToGetCurrentWorkingDirectory extends Exception
{
     public function __construct(Throwable $previous = null)
     {
         parent::__construct(
             'Failed to get current working directory. (getcwd() returned false)',
             0,
             $previous
         );
     }
}
