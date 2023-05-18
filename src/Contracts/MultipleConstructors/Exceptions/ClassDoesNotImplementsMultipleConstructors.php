<?php

namespace Wordless\Contracts\MultipleConstructors\Exceptions;

use DomainException;
use Throwable;
use Wordless\Contracts\MultipleConstructors\IMultipleConstructors;
use Wordless\Contracts\MultipleConstructors\Traits\MultipleConstructorsGuesser;
use Wordless\Enums\ExceptionCode;

class ClassDoesNotImplementsMultipleConstructors extends DomainException
{
    public function __construct(string $namespaced_class, ?Throwable $previous = null)
    {
        parent::__construct(
            "The class $namespaced_class does not implements "
            . IMultipleConstructors::class
            . ' but is using '
            . MultipleConstructorsGuesser::class,
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
