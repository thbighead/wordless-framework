<?php

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class ShowEnvironmentOnAdminBar extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'myCustomFunction';

    public static function myCustomFunction($someArgument)
    {
        // Do something. This is only called if you add this class to config/bootables.php.
    }

    protected static function hook(): ActionHook
    {
        return Action::init;
    }
}
