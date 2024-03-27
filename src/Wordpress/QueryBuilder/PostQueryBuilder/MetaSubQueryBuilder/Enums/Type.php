<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums;

enum Type: string
{
    public const KEY = 'type';

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
