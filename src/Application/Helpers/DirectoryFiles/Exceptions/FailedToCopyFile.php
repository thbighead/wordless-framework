<?php

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCopyFile extends ErrorException
{
    public function __construct(
        private readonly string $from,
        private readonly string $to,
        private readonly bool   $secure_mode,
        ?Throwable              $previous = null
    )
    {
        $security_word_mode = $this->secure_mode ? 'secure' : 'insecure';

        parent::__construct(
            "Failed to copy from $this->from to $this->to in $security_word_mode mode.",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getSecureMode(): bool
    {
        return $this->secure_mode;
    }

    public function getTo(): string
    {
        return $this->to;
    }
}
