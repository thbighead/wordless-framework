<?php

namespace Wordless\Application\Guessers;

use Wordless\Core\Composer;
use Wordless\Infrastructure\Guesser;

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
