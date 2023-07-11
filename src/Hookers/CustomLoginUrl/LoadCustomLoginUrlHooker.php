<?php

namespace Wordless\Hookers\CustomLoginUrl;

class LoadCustomLoginUrlHooker extends CustomLoginUrlHooker
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'load';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'plugins_loaded';
    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = 1;
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'action';

    public static function load()
    {
        if (self::canHook()) {
            global $pagenow;
            $request = parse_url(rawurldecode($_SERVER['REQUEST_URI']));

            if (self::isRequestTheSameAsCustomLogin($request)) {
                $pagenow = 'wp-login.php';
            }
        }
    }

    private static function isRequestTheSameAsCustomLogin($request): bool
    {
        return isset($request['path'])
            && (untrailingslashit($request['path']) === home_url(self::newLoginSlug(), 'relative'));
    }
}
