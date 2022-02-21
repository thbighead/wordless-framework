<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;

class HooksDebugLog extends AbstractHooker
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
        if (WP_DEBUG_LOG) {
            error_log("HOOK: $hook_name - " . date('Y-m-d H:i:s'));
        }
	}
}
