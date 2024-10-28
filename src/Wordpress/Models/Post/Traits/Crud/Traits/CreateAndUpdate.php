<?php

namespace Wordless\Wordpress\Models\Post\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder\CreateBuilder;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder\UpdateBuilder;

trait CreateAndUpdate
{
    public static function buildEdit(int $post_id, string $title): Builder
    {
        return new UpdateBuilder($post_id, $title, static::TYPE_KEY);
    }

    public static function buildNew(string $title): Builder
    {
        return new CreateBuilder($title, static::TYPE_KEY);
    }
}
