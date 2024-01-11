<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums;

enum Type: string
{
    case type_binary = 'BINARY';
    case type_char = 'CHAR';
    case type_date = 'DATE';
    case type_datetime = 'DATETIME';
    case type_decimal = 'DECIMAL';
    case type_numeric = 'NUMERIC';
    case type_signed = 'SIGNED';
    case type_time = 'TIME';
    case type_unsigned = 'UNSIGNED';
}
