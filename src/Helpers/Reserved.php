<?php

namespace Wordless\Helpers;

class Reserved
{
    private const RESERVED_BY_WORDPRESS = [
        'post' => true,
        'page' => true,
        'attachment' => true,
        'revision' => true,
        'nav_menu_item' => true,
        'custom_css' => true,
        'customize_changeset' => true,
        'oembed_cache' => true,
        'user_request' => true,
        'wp_block' => true,
        'action' => true,
        'author' => true,
        'order' => true,
        'theme' => true,
    ];

    public static function getReservedPostTypeKeys(): array
    {
        return array_keys(self::RESERVED_BY_WORDPRESS);
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#reserved-post-types
     *
     * @param string $post_type
     * @return bool
     */
    public static function isPostTypeReservedByWordPress(string $post_type): bool
    {
        return isset(self::RESERVED_BY_WORDPRESS[$post_type]);
    }
}
