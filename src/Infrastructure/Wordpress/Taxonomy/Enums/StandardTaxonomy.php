<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Enums;

enum StandardTaxonomy
{
    final public const ANY = null;

    case category;
    case tag;
}
