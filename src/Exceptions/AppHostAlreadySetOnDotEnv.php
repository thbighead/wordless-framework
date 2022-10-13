<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class AppHostAlreadySetOnDotEnv extends Exception
{
    public function __construct(string $app_host, Throwable $previous = null)
    {
        parent::__construct("APP_HOST already set in .env file as '$app_host'.", 0, $previous);
    }
}
