<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\Enums;

enum Relation: string
{
    public const KEY = 'relation';

    case and = 'AND';
    case or = 'OR';
}
