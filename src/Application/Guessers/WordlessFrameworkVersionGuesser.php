<?php declare(strict_types=1);

namespace Wordless\Application\Guessers;

use Wordless\Core\Composer\Main;
use Wordless\Infrastructure\Guesser;

class WordlessFrameworkVersionGuesser extends Guesser
{
    protected function guessValue(): string
    {
        return Main::getFrameworkInstalledVersion();
    }
}
