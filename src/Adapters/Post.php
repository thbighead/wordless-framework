<?php

namespace Wordless\Adapters;

use Wordless\Contracts\Adapter\WithAcfs;
use Wordless\Helpers\Arr;
use WP_Post;

/**
 * @mixin WP_Post
 */
class Post
{
    use WithAcfs;

    /** @var Category[] $categories */
    private array $categories;
    private WP_Post $wpPost;

    /**
     * @param WP_Post|int $post
     * @param bool $with_acfs
     */
    public function __construct($post, bool $with_acfs = true)
    {
        $this->wpPost = $post instanceof WP_Post ? $post : get_post($post);

        if ($with_acfs) {
            $this->loadAcfs($this->wpPost->ID);
        }
    }

    public static function __callStatic(string $method_name, array $arguments)
    {
        return WP_Post::$method_name(...$arguments);
    }

    /**
     * @param WP_Post|int $post
     * @param bool $with_acfs
     * @return static
     * @noinspection PhpMissingReturnTypeInspection
     */
    public static function get($post, bool $with_acfs = true)
    {
        return new static($post, $with_acfs);
    }

    public function __call(string $method_name, array $arguments)
    {
        return $this->wpPost->$method_name(...$arguments);
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
