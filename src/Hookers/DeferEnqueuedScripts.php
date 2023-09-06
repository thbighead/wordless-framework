<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\Hooker;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\Str;
use Wordless\Helpers\Url;
use Wordless\Hookers\CustomLoginUrl\CustomLoginUrlHooker;

class DeferEnqueuedScripts extends Hooker
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

    /**
     * @param string $tag
     * @return string
     * @throws PathNotFoundException
     */
    public static function addReferToScriptTag(string $tag): string
    {
        if (Url::isCurrentAdminLogin() || is_admin()) {
            return $tag;
        }

        if (!Str::contains($tag, self::DEFER_ATTRIBUTE)) {
            $search = '<script ';

            return Str::replace($tag, $search, $search . self::DEFER_ATTRIBUTE);
        }

        return $tag;
    }
}
