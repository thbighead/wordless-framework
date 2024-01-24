<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums;

enum LogicCompare: string
{
    case equals = '=';
    case greater_than = '>';
    case greater_than_or_equals = '>=';
    case less_than = '<';
    case less_than_or_equals = '<=';
    case not_equalt = '!=';

}
