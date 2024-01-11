<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;


use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;

class RemoveGlobalCustomInlineStyles extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeInlineCss';

    public static function removeInlineCss(): void
    {
        wp_dequeue_style('global-styles');
    }

    protected static function hook(): ActionHook
    {
        return Action::wp_enqueue_scripts;
    }
}
