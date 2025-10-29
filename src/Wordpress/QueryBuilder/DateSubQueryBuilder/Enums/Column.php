<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums;

enum Column
{
    public const KEY = 'column';

    case post_modified;
    case post_modified_gmt;
    case post_date;
    case post_date_gmt;
}
