<?php

namespace Wordless\Contracts\IMultipleConstructors\Exceptions;

use DomainException;
use Throwable;
use Wordless\Contracts\IMultipleConstructors;
use Wordless\Contracts\IMultipleConstructors\Traits\MultipleConstructorsGuesser;
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
