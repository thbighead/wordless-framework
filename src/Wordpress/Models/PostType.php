<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Models\PostType\Traits\MixinWpPostType;
use Wordless\Wordpress\Models\PostType\Traits\Repository;
use WP_Post_Type;

/**
 * @mixin WP_Post_Type
 */
class PostType
{
    use MixinWpPostType;
    use Repository;

    final public const KEY_MAX_LENGTH = 20;
    final public const QUERY_TYPE_KEY = 'post_type';

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

        if (!(($wpPostType = get_post_type_object($post_type)) instanceof WP_Post_Type)) {
            throw new PostTypeNotRegistered($post_type);
        }

        $this->wpPostType = $wpPostType;
    }

    public function getPermissions(): array
    {
        return (array)$this->cap;
    }

    public function is(PostType|StandardType|string $type): bool
    {
        if (!is_string($type)) {
            $type = $type->name;
        }

        return $this->name === $type;
    }
}
