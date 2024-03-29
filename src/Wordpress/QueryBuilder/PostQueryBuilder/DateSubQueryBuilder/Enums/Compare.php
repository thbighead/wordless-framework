<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums;

enum Compare: string
{
    public const KEY = 'compare';

    case equals = '=';
    case greater_than = '>';
    case greater_than_or_equals = '>=';
    case less_than = '<';
    case less_than_or_equals = '<=';
    case not_equals = '!=';
    case between = 'BETWEEN';
    case in = 'IN';
    case not_between = 'NOT BETWEEN';
    case not_in = 'NOT IN';
}
