<?php

namespace Wordless\Application\Hookers;

use Wordless\Infrastructure\Hooker;

class RemoveEmojiFromWpResourceHints extends Hooker
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 2;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'disableDns';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'wp_resource_hints';
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    public static function disableDns(array $urls, string $relation_type): array
    {
        if ($relation_type === 'dns-prefetch') {
            /** This filter is documented in wp-includes/formatting.php */
            $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

            $urls = array_diff($urls, [$emoji_svg_url]);
        }

        return $urls;
    }
}
