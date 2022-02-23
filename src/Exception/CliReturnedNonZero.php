<?php

namespace Wordless\Exception;

use Exception;
use Throwable;

class CliReturnedNonZero extends Exception
{
    public function __construct(string $command, int $command_result, Throwable $previous = null)
    {
        parent::__construct("Running $command returned non-zero ($command_result)", 0, $previous);
    }
}