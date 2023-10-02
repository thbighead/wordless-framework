<?php

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Listener;

class HooksDebugLog extends Listener
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'debugLog';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'all';

    public static function debugLog(string $hook_name)
    {
        if (WORDLESS_HOOK_DEBUG) {
            error_log("HOOK: $hook_name - " . date('Y-m-d H:i:s'));
        }
    }
}
