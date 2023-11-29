<?php

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Listener;

class RemoveGlobalCustomInlineStyles extends Listener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeInlineCss';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'wp_enqueue_scripts';

    public static function removeInlineCss(): void
    {
        wp_dequeue_style('global-styles');
    }
}
