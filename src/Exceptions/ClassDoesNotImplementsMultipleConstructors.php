<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Contracts\MultipleConstructors;
use Wordless\Contracts\MultipleConstructorsGuesser;

class ClassDoesNotImplementsMultipleConstructors extends Exception
{
    public function __construct(string $namespaced_class, Throwable $previous = null)
    {
        parent::__construct(
            "The class $namespaced_class does not implements "
            . MultipleConstructors::class
            . ' but is using '
            . MultipleConstructorsGuesser::class,
            0,
            $previous
        );
    }
}
