<?php

namespace Wordless\Application\Helpers;

use WP;

class Reserved
{
    private static WP $wordpress;
    /** @var array<string, bool> $forbidden_taxonomy_names */
    private static array $forbidden_taxonomy_names;
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
     * @return array<string, bool>
     */
    public static function getReservedTaxonomyNames(): array
    {
        return self::$forbidden_taxonomy_names ?? self::$forbidden_taxonomy_names = self::mountReservedTaxonomyNames();
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

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#reserved-post-types
     *
     * @param string $post_type
     * @return bool
     */
    public static function isTaxonomyReservedByWordPress(string $post_type): bool
    {
        return isset(self::getReservedTaxonomyNames()[$post_type]);
    }

    private static function getWordPress(): WP
    {
        return self::$wordpress ?? self::$wordpress = new WP;
    }

    /**
     * @return array<string, bool>
     */
    private static function mountReservedTaxonomyNames(): array
    {
        $also_forbidden = [
            'category' => true,
            'tag' => true,
        ] + self::RESERVED_BY_WORDPRESS;

        if (!is_array($reserved = array_combine(
            $keys = array_merge(self::getWordPress()->public_query_vars, self::getWordPress()->private_query_vars),
            array_fill(0, count($keys), true)
        ))) {
            return $also_forbidden;
        }

        return $also_forbidden + $reserved;
    }
}
