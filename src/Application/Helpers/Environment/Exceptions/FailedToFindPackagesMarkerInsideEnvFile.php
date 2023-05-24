<?php

namespace Wordless\Application\Helpers\Environment\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Application\Helpers\Environment;
use Wordless\Enums\ExceptionCode;

class FailedToFindPackagesMarkerInsideEnvFile extends ErrorException
{
    public function __construct(private readonly string $dot_env_filepath, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to find the package marker inside $this->dot_env_filepath. Write the following on it:"
            . PHP_EOL
            . Environment::PACKAGES_MARKER,
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }

    public function getDotEnvFilepath(): string
    {
        return $this->dot_env_filepath;
    }
}
