<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums;

enum Field
{
    public const KEY = 'field';

    case name;
    case slug;
    case term_id;
    case term_taxonomy_id;
}
