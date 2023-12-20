<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Listeners\RemoveEmojiFromTinyMce;
use Wordless\Application\Listeners\RemoveEmojiFromWpResourceHints;
use Wordless\Infrastructure\Provider;

class EmojiProvider extends Provider
{
    private const CONFIG_PREFIX = 'wordpress.admin.';

    /**
     * @return string[]
     */
    public function registerListeners(): array
    {
        return [
            RemoveEmojiFromTinyMce::class,
            RemoveEmojiFromWpResourceHints::class,
        ];
    }
}
