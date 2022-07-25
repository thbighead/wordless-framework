<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;

class DeferMainScript extends AbstractHooker
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'addReferToScriptTag';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'clean_url';
    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = 20;
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    public static function addReferToScriptTag($url)
    {
        if (strpos($url, 'main.js')) {
            return "$url' defer='true";
        }
        return $url;
    }
}
