<?php

namespace Wordless\Exception;

use Exception;
use Throwable;

class FailedToInstallTestEnvironmentThroughComposer extends Exception
{
    public function __construct(string $composer_command, Throwable $previous = null)
    {
        parent::__construct(
            "Failed to create test environment using the following Composer command: $composer_command",
            0,
            $previous
        );
    }
}