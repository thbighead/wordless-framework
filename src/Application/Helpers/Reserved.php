<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Reserved\Enums\PostTypeKey;
use Wordless\Application\Helpers\Reserved\Traits\Internal;

class Reserved
{
    use Internal;

    public static function isPostStatusUsedByWordPress(string $post_status): bool
    {
        return (self::isUsedByWordpressCore($post_status) ?? false) !== false;
    }

    public static function isPostTypeUsedByWordPress(string $post_type): bool
    {
        return (self::isUsedByWordpressCore($post_type) ??
                PostTypeKey::tryFrom($post_type) ??
                false) !== false;
    }

    public static function isTaxonomyUsedByWordPress(string $taxonomy): bool
    {
        return (self::isUsedByWordpressCore($taxonomy) ?? false) !== false;
    }
}
