<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Reserved\Enums;

/**
 * https://developer.wordpress.org/reference/functions/register_post_type/#reserved-post-types
 */
enum PostTypeKey: string
{
    // The following post types are reserved and are already used by WordPress.
    case post = 'post';
    case page = 'page';
    case attachment = 'attachment';
    case revision = 'revision';
    case nav_menu_item = 'nav_menu_item';
    case custom_css = 'custom_css';
    case customize_changeset = 'customize_changeset';
    case oembed_cache = 'oembed_cache';
    case user_request = 'user_request';
    case wp_block = 'wp_block';
    // In addition, the following post types should not be used as they interfere with other WordPress functions.
    case action = 'action';
    case author = 'author';
    case order = 'order';
    case theme = 'theme';
}
