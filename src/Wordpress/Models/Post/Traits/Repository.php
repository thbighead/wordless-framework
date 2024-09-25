<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits;

trait Repository
{
    /**
     * @param bool $with_acfs
     * @return static[]
     */
    public static function getAll(bool $with_acfs = true): array
    {
        $all = [];

        foreach (get_posts(['post_type' => static::TYPE_KEY]) as $post) {
            $all[] = new static($post, $with_acfs);
        }

        return $all;
    }

    public static function noneCreated(): bool
    {
        return count(static::getAll(false)) <= 1;
    }
}
