<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\PostType\Traits;

use WP_Post_Type;

trait MixinWpPostType
{
    private WP_Post_Type $wpPostType;

    public function __call(string $method_name, array $arguments)
    {
        return $this->wpPostType->$method_name(...$arguments);
    }

    public function __get(string $attribute)
    {
        return $this->wpPostType->$attribute;
    }

    public function asWpPostType(): WP_Post_Type
    {
        return $this->wpPostType;
    }
}
