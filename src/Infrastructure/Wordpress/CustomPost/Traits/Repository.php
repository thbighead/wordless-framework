<?php

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits;

use stdClass;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;

trait Repository
{
    public static function getPermissions(): array
    {
        try {
            return (array)((new PostType(static::getTypeKey()))->cap ?? new stdClass);
        } catch (PostTypeNotRegistered) {
            return [];
        }
    }
}
