<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Contracts\MultipleConstructors;

class ConstructorNotImplemented extends Exception
{
    private string $namespaced_class;
    private string $constructor_method;

    public function __construct(string $namespaced_class, string $constructor_method, Throwable $previous = null)
    {
        $this->namespaced_class = $namespaced_class;
        $this->constructor_method = $constructor_method;

        parent::__construct(
            "The class $this->namespaced_class tried to construct an object using $this->constructor_method construction method calculated by "
            . MultipleConstructors::class
            . ' but it\'s not implemented.',
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function getConstructorMethod(): string
    {
        return $this->constructor_method;
    }

    /**
     * @return string
     */
    public function getNamespacedClass(): string
    {
        return $this->namespaced_class;
    }
}
