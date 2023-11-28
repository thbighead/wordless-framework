<?php

namespace Wordless\Infrastructure\Wordpress\Listener;

use Wordless\Infrastructure\Wordpress\Listener;

abstract class AjaxListener extends Listener
{
    /**
     * Define wp_ajax_ prefixed hook to let AJAX be called from admin panel (only logged in)
     */
    protected const AVAILABLE_TO_ADMIN_PANEL = false;
    /**
     * Define wp_ajax_nopriv_ prefixed hook to let AJAX be called from frontend application (log in not an obligation)
     */
    protected const AVAILABLE_TO_FRONTEND = true;
    private const WP_AJAX_NOPRIV_PREFIX = 'wp_ajax_nopriv_';
    private const WP_AJAX_PREFIX = 'wp_ajax_';

    public static function hookIt()
    {
        $hook_addition_function = 'add_' . static::TYPE;

        if (self::AVAILABLE_TO_FRONTEND) {
            $hook_addition_function(
                self::WP_AJAX_NOPRIV_PREFIX . static::HOOK,
                [static::class, static::FUNCTION],
                static::HOOK_PRIORITY,
                static::ACCEPTED_NUMBER_OF_ARGUMENTS
            );
        }

        if (self::AVAILABLE_TO_ADMIN_PANEL) {
            $hook_addition_function(
                self::WP_AJAX_PREFIX . static::HOOK,
                [static::class, static::FUNCTION],
                static::HOOK_PRIORITY,
                static::ACCEPTED_NUMBER_OF_ARGUMENTS
            );
        }
    }
}
