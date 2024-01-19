<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums;

enum Type: string
{
    case binary = 'BINARY';
    case char = 'CHAR';
    case date = 'DATE';
    case datetime = 'DATETIME';
    case decimal = 'DECIMAL';
    case numeric = 'NUMERIC';
    case signed = 'SIGNED';
    case time = 'TIME';
    case unsigned = 'UNSIGNED';
}
