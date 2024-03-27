<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;


use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;

class RemoveEmojiFromWpResourceHints extends FilterListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'disableDns';

    public static function disableDns(array $urls, string $relation_type): array
    {
        if ($relation_type === 'dns-prefetch') {
            /** This filter is documented in wp-includes/formatting.php */
            $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

            $urls = array_diff($urls, [$emoji_svg_url]);
        }

        return $urls;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 2;
    }

    protected static function hook(): FilterHook
    {
        return Filter::wp_resource_hints;
    }
}
