<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Post\Traits\Categories;
use Wordless\Wordpress\Models\Post\Traits\MixinWpPost;
use Wordless\Wordpress\Models\Post\Traits\Repository;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
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

    private const TYPE_KEY = StandardType::post->name;

    protected PostType $type;
    protected PostStatus $status;

    /**
     * @param WP_Post|int $post
     * @param bool $with_acfs
     * @return static
     * @throws InitializingModelWithWrongPostType|PostTypeNotRegistered
     */
    public static function get(WP_Post|int $post, bool $with_acfs = true): static
    {
        return new static($post, $with_acfs);
    }

    public static function objectType(): ObjectType
    {
        return ObjectType::post;
    }

    /**
     * @param WP_Post|int $post
     * @param bool $with_acfs
     * @throws InitializingModelWithWrongPostType
     * @throws PostType\Exceptions\PostTypeNotRegistered
     */
    public function __construct(WP_Post|int $post, bool $with_acfs = true)
    {
        $this->wpPost = $post instanceof WP_Post ? $post : get_post($post);
        $this->type = new PostType($this->post_type);

        if (!$this->type->is(static::TYPE_KEY)) {
            throw new InitializingModelWithWrongPostType($this, $with_acfs);
        }

        if ($with_acfs) {
            $this->loadAcfs($this->wpPost->ID);
        }
    }

    public function getStatus(): PostStatus
    {
        return $this->status ?? $this->status = new PostStatus($this->post_status);
    }

    public function getType(): PostType
    {
        return $this->type;
    }
}
