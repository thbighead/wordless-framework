<?php

namespace Wordless\Application\Guessers;

use Wordless\Infrastructure\Guesser\ControllerGuesser;

class ControllerVersionGuesser extends ControllerGuesser
{
    /**
     * @return string|null
     * @noinspection PhpMissingReturnTypeInspection
     */
    protected function guessValue()
    {
        preg_match(
            "/Controllers\\\([^\\\]*\\\)*V(.+)\\\[\w]+Controller/",
            $this->controller_namespace_class,
            $matches
        );

        return empty($result = $matches[2] ?? null) ? null : $result;
    }
}
