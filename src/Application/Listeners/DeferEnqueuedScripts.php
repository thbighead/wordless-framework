<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Listener;

class DeferEnqueuedScripts extends Listener
{
    private const DEFER_ATTRIBUTE = 'defer=\'true';
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

    public static function addReferToScriptTag(string $url): string
    {
        if (is_admin() || !Str::endsWith(Str::beforeLast($url, '?'), '.js')) {
            return $url;
        }

        if (!Str::contains($url, self::DEFER_ATTRIBUTE)) {
            return "$url' " . self::DEFER_ATTRIBUTE;
        }

        return $url;
    }
}
