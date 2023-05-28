<?php

namespace Wordless\Application\Guessers;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Guessers\ControllerGuesser;

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
