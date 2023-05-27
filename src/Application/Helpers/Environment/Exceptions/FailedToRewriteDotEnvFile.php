<?php

namespace Wordless\Application\Helpers\Environment\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToRewriteDotEnvFile extends ErrorException
{
    public function __construct(
        private readonly string $dot_env_filepath,
        private readonly string $dot_env_new_content,
        ?Throwable              $previous = null
    )
    {
        parent::__construct(
            "Couldn't write the following content into $this->dot_env_filepath:"
            . PHP_EOL
            . $this->dot_env_new_content,
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }

    public function getDotEnvFilepath(): string
    {
        return $this->dot_env_filepath;
    }

    public function getDotEnvNewContent(): string
    {
        return $this->dot_env_new_content;
    }
}
