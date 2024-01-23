<?php declare(strict_types=1);

namespace Wordless\Application\Guessers;

use Wordless\Infrastructure\Guesser\ControllerGuesser;

class ControllerVersionGuesser extends ControllerGuesser
{
    protected function guessValue(): ?string
    {
        preg_match(
            "/Controllers\\\([^\\\]*\\\)*V(.+)\\\[\w]+Controller/",
            $this->controller_namespace_class,
            $matches
        );

        return empty($result = $matches[2] ?? null) ? null : $result;
    }
}
