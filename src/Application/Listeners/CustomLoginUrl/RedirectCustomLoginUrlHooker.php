<?php

namespace Wordless\Application\Listeners\CustomLoginUrl;

use Wordless\Exceptions\PathNotFoundException;

class RedirectCustomLoginUrlHooker extends CustomLoginUrlHooker
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'load';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'wp_redirect';

    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    /**
     * @param string $location
     * @return string
     * @throws PathNotFoundException
     */
    public static function load(string $location): string
    {
        if (self::canHook()) {
            return self::filterWpLoginPhp($location);
        }

        return $location;
    }
}
