<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\Hooker;
use Wordless\Abstractions\EnqueueableElements\EnqueueableScript;
use Wordless\Abstractions\EnqueueableElements\EnqueueableStyle;
use Wordless\Exceptions\PathNotFoundException;

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
