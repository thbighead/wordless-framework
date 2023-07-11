<?php

namespace Wordless\Hookers\CustomLoginUrl;

class WelcomeEmailWithCustomLoginUrlHooker extends CustomLoginUrlHooker
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
    protected const HOOK = 'site_option_welcome_email';

    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    public static function load($value)
    {
        if (self::canHook()) {
            return str_replace(
                'wp-login.php',
                trailingslashit(self::newLoginSlug()),
                $value
            );
        }

        return $value;
    }
}
