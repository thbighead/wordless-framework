<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Enums;

enum StandardTaxonomy: string
{
    final public const ANY = null;

    case category = 'category';
    case tag = 'post_tag';
}
