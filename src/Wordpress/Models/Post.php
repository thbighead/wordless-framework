<?php

namespace Wordless\Wordpress\Models;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Enums\MetableObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Traits\WithAcfs;
use WP_Post;

/**
 * @mixin WP_Post
 */
class Post implements IRelatedMetaData
{
    use WithAcfs, WithMetaData;

    /** @var Category[] $categories */
    private array $categories;
    private WP_Post $wpPost;

    public static function __callStatic(string $method_name, array $arguments)
    {
        return WP_Post::$method_name(...$arguments);
    }

    public static function get(WP_Post|int $post, bool $with_acfs = true): static
    {
        return new static($post, $with_acfs);
    }

    public static function objectType(): MetableObjectType
    {
        return MetableObjectType::post;
    }

    public function __call(string $method_name, array $arguments)
    {
        return $this->wpPost->$method_name(...$arguments);
    }

    public function __construct(WP_Post|int $post, bool $with_acfs = true)
    {
        $this->wpPost = $post instanceof WP_Post ? $post : get_post($post);

        if ($with_acfs) {
            $this->loadAcfs($this->wpPost->ID);
        }
    }

    public function __get(string $attribute)
    {
        return $this->wpPost->$attribute;
    }

    public function asWpPost(): WP_Post
    {
        return $this->wpPost;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        if (isset($this->categories)) {
            return $this->categories;
        }

        $this->categories = [];

        foreach ($this->getCategoriesIds() as $category_id) {
            $this->categories[] = Category::getById($category_id);
        }

        return $this->categories;
    }

    public function getCategory(): ?Category
    {
        return $this->getCategories()[0] ?? null;
    }

    /**
     * @return int[]
     */
    private function getCategoriesIds(): array
    {
        return Arr::wrap($this->wpPost->post_category);
    }
}
