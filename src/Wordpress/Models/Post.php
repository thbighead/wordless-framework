<?php

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Enums\MetableObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Post\Traits\Categories;
use Wordless\Wordpress\Models\Post\Traits\MixinWpPost;
use Wordless\Wordpress\Models\Post\Traits\Repository;
use Wordless\Wordpress\Models\Traits\WithAcfs;
use WP_Post;

/**
 * @mixin WP_Post
 */
class Post implements IRelatedMetaData
{
    use Categories;
    use MixinWpPost;
    use Repository;
    use WithAcfs;
    use WithMetaData;

    public static function get(WP_Post|int $post, bool $with_acfs = true): static
    {
        return new static($post, $with_acfs);
    }

    public static function objectType(): MetableObjectType
    {
        return MetableObjectType::post;
    }

    public function __construct(WP_Post|int $post, bool $with_acfs = true)
    {
        $this->wpPost = $post instanceof WP_Post ? $post : get_post($post);

        if ($with_acfs) {
            $this->loadAcfs($this->wpPost->ID);
        }
    }
}
