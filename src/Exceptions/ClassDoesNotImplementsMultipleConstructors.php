<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Contracts\IMultipleConstructors;
use Wordless\Contracts\MultipleConstructors;

class ClassDoesNotImplementsMultipleConstructors extends Exception
{
    public function __construct(string $namespaced_class, Throwable $previous = null)
    {
        parent::__construct(
            "The class $namespaced_class does not implements "
            . IMultipleConstructors::class
            . ' but is using '
            . MultipleConstructors::class,
            0,
            $previous
        );
    }
}
