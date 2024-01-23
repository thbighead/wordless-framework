<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums;

enum Compare: string
{
    case between = 'BETWEEN';
    case equals = '=';
    case greater_than = '>';
    case greater_than_or_equals = '>=';
    case less_than = '<';
    case less_than_or_equals = '<=';
    case in = 'IN';
    case not_between = 'NOT BETWEEN';
    case not_equalt = '!=';
    case not_in = 'NOT IN';
}
