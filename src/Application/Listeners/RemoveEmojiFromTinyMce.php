<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;


use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Filter;

class RemoveEmojiFromTinyMce extends FilterListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeEmojis';

    public static function removeEmojis($plugins): array
    {
        if (is_array($plugins)) {
            return array_diff($plugins, ['wpemoji']);
        }

        return [];
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): FilterHook
    {
        return Filter::tiny_mce_plugins;
    }
}
