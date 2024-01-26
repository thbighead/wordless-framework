<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums;

enum Column
{
    public const KEY = 'column';

    case post_modified;
    case post_modified_gmt;
    case post_date;
    case post_date_gmt;
}
