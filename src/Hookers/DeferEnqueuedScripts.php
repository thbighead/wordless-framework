<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;
use Wordless\Helpers\Str;

class DeferEnqueuedScripts extends AbstractHooker
{
    private const DEFER_ATTRIBUTE = 'defer="true" ';
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
    protected const HOOK = 'script_loader_tag';
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    public static function addReferToScriptTag(string $tag): string
    {
        if ((isset($_SERVER['SCRIPT_NAME']) && stripos(wp_login_url(), $_SERVER['SCRIPT_NAME']) !== false) || is_admin()) {
            return $tag;
        }

        if (!Str::contains($tag, self::DEFER_ATTRIBUTE)) {
            $search = '<script ';

            return Str::replace($tag, $search, $search . self::DEFER_ATTRIBUTE);
        }

        return $tag;
    }
}
