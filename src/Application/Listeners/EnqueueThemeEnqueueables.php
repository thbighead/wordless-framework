<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class EnqueueThemeEnqueueables extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'enqueue';

    public static function enqueue(): void
    {
        // TODO: What is that???? Seems incomplete
    }

    protected static function hook(): ActionHook
    {
        return Action::wp_enqueue_scripts;
    }
}
