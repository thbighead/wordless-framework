<?php

namespace Wordless\Application\Helpers\Reserved\Traits;

use Wordless\Application\Helpers\Reserved\Enums\HandleSlug;
use Wordless\Application\Helpers\Reserved\Enums\Term;

trait Internal
{
    private static function isUsedByWordpressCore(string $term): ?bool
    {
        return (Term::tryFrom($term) ?? HandleSlug::tryFrom($term)) === null ? null : true;
    }
}
