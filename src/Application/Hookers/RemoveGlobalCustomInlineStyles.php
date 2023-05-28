<?php

namespace Wordless\Application\Hookers;

use Wordless\Infrastructure\Wordpress\Hooker;

class RemoveGlobalCustomInlineStyles extends Hooker
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeInlineCss';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'wp_enqueue_scripts';

    public static function removeInlineCss()
    {
        wp_dequeue_style('global-styles');
    }
}
