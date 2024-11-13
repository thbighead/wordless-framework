<?php

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class RemoveClassicThemeStylesInlineCss extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'dequeueClassicThemeStyles';

    public static function dequeueClassicThemeStyles(): void
    {
        wp_dequeue_style('classic-theme-styles');
    }

    protected static function hook(): ActionHook
    {
        return Action::wp_enqueue_scripts;
    }
}
