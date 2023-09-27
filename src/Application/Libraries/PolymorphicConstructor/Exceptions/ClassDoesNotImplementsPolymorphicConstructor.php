<?php

namespace Wordless\Application\Libraries\PolymorphicConstructor\Exceptions;

use DomainException;
use Throwable;
use Wordless\Application\Libraries\PolymorphicConstructor\Contracts\IPolymorphicConstructor;
use Wordless\Application\Libraries\PolymorphicConstructor\Traits\PolymorphicConstructorGuesser;
use Wordless\Infrastructure\Enums\ExceptionCode;

class ClassDoesNotImplementsPolymorphicConstructor extends DomainException
{
    public function __construct(string $namespaced_class, ?Throwable $previous = null)
    {
        parent::__construct(
            "The class $namespaced_class does not implements "
            . IPolymorphicConstructor::class
            . ' but is using '
            . PolymorphicConstructorGuesser::class,
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
