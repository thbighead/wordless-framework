<?php

namespace Wordless\Wordpress\Models;

use Wordless\Exceptions\PostTypeNotRegistered;
use WP_Post_Type;

/**
 * @mixin WP_Post_Type
 */
class PostType
{
    public const ANY = 'any';
    public const ATTACHMENT = 'attachment';
    public const NAVIGATION_MENU_ITEM = 'nav_menu_item';
    public const PAGE = 'page';
    public const POST = 'post';
    public const QUERY_TYPE_KEY = 'post_type';
    public const REVISION = 'revision';

    private WP_Post_Type $wpPostType;

    /**
     * @return static[]
     */
    public static function getAllCustom(): array
    {
        $customPostTypes = [];

        foreach (get_post_types(['_builtin' => false]) as $custom_post_type_key) {
            try {
                $customPostTypes[] = new static($custom_post_type_key);
            } catch (PostTypeNotRegistered $exception) {
                continue;
            }
        }

        return $customPostTypes;
    }

    public function __call(string $method_name, array $arguments)
    {
        return $this->wpPostType->$method_name(...$arguments);
    }

    /**
     * @param WP_Post_Type|string $post_type
     * @throws PostTypeNotRegistered
     */
    public function __construct(WP_Post_Type|string $post_type)
    {
        if ($post_type instanceof WP_Post_Type) {
            $this->wpPostType = $post_type;

            return;
        }

        if (($this->wpPostType = get_post_type_object($post_type)) === null) {
            throw new PostTypeNotRegistered($post_type);
        }
    }

    public function __get(string $attribute)
    {
        return $this->wpPostType->$attribute;
    }

    public function asWpPostType(): WP_Post_Type
    {
        return $this->wpPostType;
    }

    public function getPermissions(): array
    {
        return (array)$this->cap;
    }
}
