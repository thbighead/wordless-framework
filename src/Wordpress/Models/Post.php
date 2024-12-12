<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Post\Exceptions\FailedToGetPermalink;
use Wordless\Wordpress\Models\Post\Exceptions\FailedToGetPostTypeArchiveUrl;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Post\Traits\Categories;
use Wordless\Wordpress\Models\Post\Traits\FeaturedImage;
use Wordless\Wordpress\Models\Post\Traits\MixinWpPost;
use Wordless\Wordpress\Models\Post\Traits\Crud;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Models\Traits\WithAcfs;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use WP_Post;

/**
 * @mixin WP_Post
 */
class Post implements IRelatedMetaData
{
    use Categories;
    use FeaturedImage;
    use MixinWpPost;
    use Crud;
    use WithAcfs;
    use WithMetaData;

    protected const TYPE_KEY = StandardType::post->name;

    private static string $archive_url;

    protected PostStatus $status;
    protected PostType $type;
    protected string $url;

    /**
     * @return string
     * @throws FailedToGetPostTypeArchiveUrl
     */
    public static function archiveUrl(): string
    {
        if (isset(self::$archive_url)) {
            return self::$archive_url;
        }

        if (!is_string($archive_url = get_post_type_archive_link(static::TYPE_KEY))) {
            throw new FailedToGetPostTypeArchiveUrl(static::class, static::TYPE_KEY);
        }

        return self::$archive_url = $archive_url;
    }

    /**
     * @param WP_Post|int $post
     * @param bool $with_acfs
     * @return static
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidAcfFunction
     * @throws PostTypeNotRegistered
     */
    public static function get(WP_Post|int $post, bool $with_acfs = true): static
    {
        return new static($post, $with_acfs);
    }

    public static function objectType(): ObjectType
    {
        return ObjectType::post;
    }

    public static function postType(): PostType
    {
        return new PostType(static::TYPE_KEY);
    }

    /**
     * @param WP_Post|int $post
     * @param bool $with_acfs
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidAcfFunction
     * @throws PostTypeNotRegistered
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

    /**
     * @return string
     * @throws FailedToGetPermalink
     */
    public function url(): string
    {
        if (isset($this->url)) {
            return $this->url;
        }

        if (!is_string($url = get_permalink($this->asWpPost()))) {
            throw new FailedToGetPermalink($this->asWpPost());
        }

        return $this->url = $url;
    }

    final public function id(): int
    {
        return $this->ID;
    }
}
