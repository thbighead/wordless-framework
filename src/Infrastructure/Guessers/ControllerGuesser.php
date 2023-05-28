<?php

namespace Wordless\Infrastructure\Guessers;

use Wordless\Infrastructure\Guessers;

abstract class ControllerGuesser extends Guesser
{
    protected string $controller_namespace_class;

    public function __construct(string $controller_namespace_class)
    {
        $this->controller_namespace_class = $controller_namespace_class;
    }
}
