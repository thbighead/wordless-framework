<?php

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidAcfFunction extends DomainException
{
    public function __construct(public readonly string $function_name, ?Throwable $previous = null)
    {
        parent::__construct(
            "Expected ACF function '$this->function_name' does not exists",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
