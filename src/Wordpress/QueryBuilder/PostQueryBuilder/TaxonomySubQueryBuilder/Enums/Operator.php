<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums;

enum Operator: string
{
    public const KEY = 'operator';

    case and = 'AND';
    case exists = 'EXISTS';
    case in = 'IN';
    case not_exists = 'NOT EXISTS';
    case not_in = 'NOT IN';
}
