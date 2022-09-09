<?php

namespace Wordless\Abstractions\Guessers;

use Wordless\Abstractions\Composer;

class WordlessFrameworkVersionGuesser extends BaseGuesser
{
    /**
     * @inheritDoc
     */
    protected function guessValue()
    {
        return Composer::getFrameworkInstalledVersion();
    }
}
