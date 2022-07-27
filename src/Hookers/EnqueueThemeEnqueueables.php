<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;
use Wordless\Abstractions\EnqueueableElements\EnqueueableScript;
use Wordless\Abstractions\EnqueueableElements\EnqueueableStyle;
use Wordless\Exceptions\InternalCacheNotLoaded;
use Wordless\Exceptions\PathNotFoundException;

class EnqueueThemeEnqueueables extends AbstractHooker
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
     * @throws InternalCacheNotLoaded
     * @throws PathNotFoundException
     */
    public static function enqueue(): void
    {
        EnqueueableStyle::enqueueAll();
        EnqueueableScript::enqueueAll();
    }
}