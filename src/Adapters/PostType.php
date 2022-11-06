<?php

namespace Wordless\Adapters;

use Wordless\Exceptions\PostTypeNotRegistered;
use WP_Post_Type;

/**
 * @mixin WP_Post_Type
 */
class PostType
{
    private WP_Post_Type $wpPostType;

    /**
     * @param WP_Post_Type|string $post_type
     * @throws PostTypeNotRegistered
     */
    public function __construct($post_type)
    {
        if ($post_type instanceof WP_Post_Type) {
            $this->wpPostType = $post_type;

            return;
        }

        if (($this->wpPostType = get_post_type_object($post_type)) === null) {
            throw new PostTypeNotRegistered($post_type);
        }
    }

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

    public function __get(string $attribute)
    {
        return $this->wpPostType->$attribute;
    }

    /**
     * @return WP_Post_Type
     */
    public function asWpPostType(): WP_Post_Type
    {
        return $this->wpPostType;
    }

    public function getPermissions(): array
    {
        return (array)$this->cap;
    }
}
