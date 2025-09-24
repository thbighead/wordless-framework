<?php declare(strict_types=1);

namespace Wordless\Application\Guessers;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Guesser\ControllerGuesser;

class ControllerResourceNameGuesser extends ControllerGuesser
{
    protected function guessValue(): string
    {
        return (string)Str::of(Str::afterLast($this->controller_namespace_class, '\\'))
            ->before('Controller')->snakeCase();
    }
}
