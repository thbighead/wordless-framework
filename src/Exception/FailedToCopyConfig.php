<?php

namespace Wordless\Exception;

use Exception;
use Throwable;

class FailedToCopyConfig extends Exception
{
    public function __construct(string $config_filepath_from, string $config_filepath_to, Throwable $previous = null)
    {
        parent::__construct(
            "Failed to copy config file from $config_filepath_from to $config_filepath_to",
            0,
            $previous
        );
    }
}