<?php

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Reserved\Enums\HandleSlug;
use Wordless\Application\Helpers\Reserved\Enums\PostTypeKey;
use Wordless\Application\Helpers\Reserved\Enums\Term;

class Reserved
{
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

    private static function isUsedByWordpressCore(string $term): ?bool
    {
        return (Term::tryFrom($term) ?? HandleSlug::tryFrom($term)) === null ? null : true;
    }
}
