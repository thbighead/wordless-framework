<?php

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits;

use stdClass;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;

trait Repository
{
    public static function getAll(bool $with_acfs = true): array
    {
        $all = [];

        foreach (get_posts(['post_type' => static::TYPE_KEY]) as $typedPost) {
            $all[] = new static($typedPost, $with_acfs);
        }

        return $all;
    }

    public static function getPermissions(): array
    {
        try {
            return (array)((new PostType(static::getTypeKey()))->cap ?? new stdClass);
        } catch (PostTypeNotRegistered) {
            return [];
        }
    }
}
