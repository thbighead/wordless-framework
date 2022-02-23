<?php

namespace Wordless\Exception;

use Exception;
use Throwable;

class InvalidDirectory extends Exception
{
     public function __construct(string $supposed_directory_path, Throwable $previous = null)
     {
         parent::__construct("Failed to use '$supposed_directory_path' as a directory", 0, $previous);
     }
}