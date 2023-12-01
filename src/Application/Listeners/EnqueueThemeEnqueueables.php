<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Contracts\ActionHook;
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
        EnqueueableStyle::enqueueAll();
        EnqueueableScript::enqueueAll();
    }

    protected static function hook(): ActionHook
    {
        return Action::wp_enqueue_scripts;
    }
}
