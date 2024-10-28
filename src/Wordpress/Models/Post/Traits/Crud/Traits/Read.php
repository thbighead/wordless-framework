<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Post\Enums\StandardStatus;

trait Read
{
    /**
     * @param int $quantity
     * @return static[]
     */
    public static function firstPublished(int $quantity = 1): array
    {
        $posts = [];

        foreach (get_posts([
            'posts_per_page' => $quantity,
            'post_type' => static::TYPE_KEY,
            'post_status' => StandardStatus::publish->value,
        ]) as $casePost) {
            $posts[] = new static($casePost);
        }

        return $posts;
    }

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
