<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums;

enum Relation: string
{
    public const KEY = 'relation';

    case and = 'AND';
    case or = 'OR';
}
