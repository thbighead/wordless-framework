<?php

namespace Wordless\Infrastructure\Wordpress\CustomPostStatus\Traits\Register\Traits;

use Wordless\Application\Helpers\Reserved;

trait Validation
{
    private static function validateName(): void
    {
        if (Reserved::isPostStatusUsedByWordPress($type_key = static::getTypeKey())) {
            throw new ReservedCustomPostTypeKey($type_key);
        }
    }
}
