<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;

class RemoveEmojiFromTinyMce extends AbstractHooker
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeEmojis';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'tiny_mce_plugins';
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    public static function removeEmojis($plugins): array
    {
        if (is_array($plugins)) {
            /** @noinspection SpellCheckingInspection */
            return array_diff($plugins, ['wpemoji']);
        }

        return [];
    }
}
