<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums;

enum Direction: string
{
    case ascending = 'ASC';
    case descending = 'DESC';
}
