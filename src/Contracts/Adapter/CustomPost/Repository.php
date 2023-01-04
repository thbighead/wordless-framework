<?php

namespace Wordless\Contracts\Adapter\CustomPost;

use stdClass;
use Wordless\Adapters\PostType;
use Wordless\Exceptions\PostTypeNotRegistered;

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
