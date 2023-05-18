<?php

namespace Wordless\Infrastructure\CustomPost\Traits;

use stdClass;
use Wordless\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Models\PostType;

trait Repository
{
    public static function getPermissions(): array
    {
        try {
            return (array)((new PostType(self::getTypeKey()))->cap ?? new stdClass);
        } catch (PostTypeNotRegistered $exception) {
            return [];
        }
    }
}
