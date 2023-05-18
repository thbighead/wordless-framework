<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Application\Helpers\Environment;

class FailedToFindPackagesMarkerInsideEnvFile extends Exception
{
    public function __construct(string $dot_env_filepath, Throwable $previous = null)
    {
        parent::__construct(
            "Failed to find the package marker inside $dot_env_filepath. Write the following on it:"
            . PHP_EOL
            . Environment::PACKAGES_MARKER,
            0,
            $previous
        );
    }
}
