<?php

namespace Wordless\Application\Hookers;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\EnqueueableAsset\EnqueueableStyle;
use Wordless\Infrastructure\Hooker;

class EnqueueThemeEnqueueables extends Hooker
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'enqueue';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'wp_enqueue_scripts';

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function enqueue(): void
    {
        EnqueueableStyle::enqueueAll();
        EnqueueableScript::enqueueAll();
    }
}
