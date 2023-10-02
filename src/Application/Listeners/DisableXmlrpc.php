<?php

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Listener;

class DisableXmlrpc extends Listener
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 0;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'disable';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'xmlrpc_enabled';
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    public static function disable(): bool
    {
        return false;
    }
}
