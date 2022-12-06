<?php

namespace Wordless\Abstractions\Guessers;

use Wordless\Abstractions\Composer;

class WordlessFrameworkVersionGuesser extends Guesser
{
    /**
     * @inheritDoc
     */
    protected function guessValue()
    {
        return Composer::getFrameworkInstalledVersion();
    }
}
