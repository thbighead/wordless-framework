<?php

namespace Wordless\Application\Commands\InitializeTestEnvironment\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToInstallTestEnvironmentThroughComposer extends ErrorException
{
    public function __construct(private readonly string $composer_command, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to create test environment using the following Composer command: $this->composer_command",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }

    public function getComposerCommand(): string
    {
        return $this->composer_command;
    }
}
