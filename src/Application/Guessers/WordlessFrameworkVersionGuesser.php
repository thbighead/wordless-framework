<?php

namespace Wordless\Application\Guessers;

use Wordless\Core\Main;
use Wordless\Infrastructure\Guesser;

class WordlessFrameworkVersionGuesser extends Guesser
{
    /**
     * @inheritDoc
     */
    protected function guessValue()
    {
        return Main::getFrameworkInstalledVersion();
    }
}
