<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums;

enum Compare: string
{
    public const KEY = 'compare';

    case between = 'BETWEEN';
    case equals = '=';
    case exists = 'EXISTS';
    case greater_than = '>';
    case greater_than_or_equals = '>=';
    case less_than = '<';
    case less_than_or_equals = '<=';
    case like = 'LIKE';
    case in = 'IN';
    case not_between = 'NOT BETWEEN';
    case not_equals = '!=';
    case not_exists = 'NOT EXISTS';
    case not_like = 'NOT LIKE';
    case not_in = 'NOT IN';
    case not_regex = 'NOT REGEXP';
    case regex = 'REGEXP';
}
