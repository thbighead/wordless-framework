<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits;

use WP_Post;

trait MixinWpPost
{
    private WP_Post $wpPost;

    public static function __callStatic(string $method_name, array $arguments)
    {
        return WP_Post::$method_name(...$arguments);
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
}
