<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\Enums;

enum OrderByDirection: string
{
    case ascending = 'ASC';
    case descending = 'DESC';
}
