<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Guessers\WordlessFrameworkVersionGuesser;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Filter;

class WordlessVersionOnAdmin extends FilterListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'writeWordlessVersions';

    public static function priority(): int
    {
        return PHP_INT_MAX;
    }

    public static function writeWordlessVersions(string $content): string
    {
        $framework_version = (new WordlessFrameworkVersionGuesser)->getValue();

        return "$content (Wordless Framework version $framework_version)";
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): FilterHook
    {
        return Filter::update_footer;
    }
}
