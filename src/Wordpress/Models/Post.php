<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Post\Exceptions\FailedToGetPermalink;
use Wordless\Wordpress\Models\Post\Exceptions\FailedToGetPostTypeArchiveUrl;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Post\Traits\Crud;
use Wordless\Wordpress\Models\Post\Traits\FeaturedImage;
use Wordless\Wordpress\Models\Post\Traits\MixinWpPost;
use Wordless\Wordpress\Models\Post\Traits\Taxonomies;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Models\Traits\Terms;
use Wordless\Wordpress\Models\Traits\WithAcfs;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use WP_Post;

/**
 * @mixin WP_Post
 */
class Post implements IRelatedMetaData
{
    use Crud;
    use FeaturedImage;
    use MixinWpPost;
    use Taxonomies;
    use Terms;
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

    public function appendTaxonomy(Taxonomy $taxonomyTerm): static
    {
        wp_set_object_terms($this->id(), $taxonomyTerm->id(), $taxonomyTerm->taxonomy, true);

        return $this;
    }

    public function getStatus(): PostStatus
    {
        return $this->status ?? $this->status = new PostStatus($this->post_status);
    }

    public function getType(): PostType
    {
        return $this->type;
    }

    public function resetTaxonomies(string $taxonomy_key): static
    {
        wp_set_object_terms($this->id(), [], $taxonomy_key);

        return $this;
    }

    public function setTaxonomies(Taxonomy $taxonomyTerm, Taxonomy ...$taxonomyTerms): static
    {
        $terms_ids_grouped_by_taxonomy_key = [];

        /** @var Taxonomy $term */
        foreach (Arr::prepend($taxonomyTerms, $taxonomyTerm) as $term) {
            $terms_ids_grouped_by_taxonomy_key[$term->taxonomy][] = $term->id();
        }

        foreach ($terms_ids_grouped_by_taxonomy_key as $taxonomy_key => $terms) {
            $taxonomy_terms_ids = [];

            foreach ($terms as $term_id) {
                $taxonomy_terms_ids[] = $term_id;
            }

            wp_set_object_terms($this->id(), $taxonomy_terms_ids, $taxonomy_key);
        }

        return $this;
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
