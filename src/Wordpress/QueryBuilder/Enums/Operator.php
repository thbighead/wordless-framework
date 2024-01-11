<?php

namespace Wordless\Wordpress\QueryBuilder\Enums;

enum Operator: string
{
    case different = '!=';
    case equal = '=';
    case greater_than = '>';
    case greater_than_or_equal = '>=';
    case less_than = '<';
    case less_than_or_equal = '<=';
    case like = 'LIKE';
}
