<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Guessers\WordlessFrameworkVersionGuesser;
use Wordless\Infrastructure\Wordpress\Listener;

class WordlessVersionOnAdmin extends Listener
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'writeWordlessVersions';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'update_footer';
    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = PHP_INT_MAX;
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    public static function writeWordlessVersions(string $content): string
    {
        $framework_version = (new WordlessFrameworkVersionGuesser)->getValue();

        return "$content (Wordless Framework version $framework_version)";
    }
}
