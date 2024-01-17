<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class EnqueueThemeEnqueueables extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'enqueue';

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function enqueue(): void
    {

    }

    protected static function hook(): ActionHook
    {
        return Action::wp_enqueue_scripts;
    }
}
