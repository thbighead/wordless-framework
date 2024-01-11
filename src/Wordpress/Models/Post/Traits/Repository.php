<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits;

trait Repository
{
    public static function getAll(bool $with_acfs = true): array
    {
        $all = [];

        foreach (get_posts() as $post) {
            $all[] = new static($post, $with_acfs);
        }

        return $all;
    }
}
