<?php

namespace Wordless\Abstractions\Guessers;

use Wordless\Helpers\Str;

class ControllerResourceNameGuesser extends ControllerGuesser
{
    protected function guessValue(): string
    {
        return Str::snakeCase(
            Str::before(
                Str::afterLast($this->controller_namespace_class, '\\'),
                'Controller'
            )
        );
    }
}
