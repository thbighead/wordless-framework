<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums;

enum Compare: string
{
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
