<?php

namespace Wordless\Contracts\IMultipleConstructors\Exceptions;

use BadMethodCallException;
use Throwable;
use Wordless\Contracts\IMultipleConstructors\Traits\MultipleConstructorsGuesser;
use Wordless\Infrastructure\Enums\ExceptionCode;

class ConstructorNotImplemented extends BadMethodCallException
{
    public function __construct(
        private readonly string $namespaced_class,
        private readonly string $constructor_method,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "The class $this->namespaced_class tried to construct an object using $this->constructor_method construction method calculated by "
            . MultipleConstructorsGuesser::class
            . ' but it\'s not implemented.',
            ExceptionCode::development_error->value,
            $previous
        );
    }

    public function getConstructorMethod(): string
    {
        return $this->constructor_method;
    }

    public function getNamespacedClass(): string
    {
        return $this->namespaced_class;
    }
}
