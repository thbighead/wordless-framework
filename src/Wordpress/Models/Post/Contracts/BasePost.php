<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Contracts;

use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Post\Exceptions\FailedToGetPermalink;
use Wordless\Wordpress\Models\Post\Exceptions\FailedToGetPostTypeArchiveUrl;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Post\Traits\Crud;
use Wordless\Wordpress\Models\Post\Traits\MixinWpPost;
use Wordless\Wordpress\Models\Post\Traits\Taxonomies;
use Wordless\Wordpress\Models\PostStatus;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Models\Traits\Terms;
use WP_Post;

/**
 * @mixin WP_Post
 */
abstract class BasePost implements IRelatedMetaData
{
    use Crud;
    use MixinWpPost;
    use Taxonomies;
    use Terms;
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
     * @return static
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public static function make(WP_Post|int $post): static
    {
        return new static($post);
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
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function __construct(WP_Post|int $post)
    {
        $this->wpPost = $post instanceof WP_Post ? $post : get_post($post);
        $this->type = new PostType($this->post_type);

        if (!$this->type->is(static::TYPE_KEY)) {
            throw new InitializingModelWithWrongPostType($this);
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

    public function toArray(): array
    {
        $array = $this->asWpPost()->to_array();

        if (isset($this->url)) {
            try {
                $array['url'] = $this->url();
            } catch (FailedToGetPermalink) {
            }
        }

        if (!empty($this->meta_fields)) {
            $array['meta'] = $this->getMetaFields();
        }

        if (!empty($this->taxonomiesTerms)) {
            $array['taxonomies_terms'] = $this->taxonomiesTermsToArray();
        }

        return $array;
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
