<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums;

enum Field: string
{
    case year = 'year';
    case month = 'monthnum';
    case week_of_year = 'w';
    case day_of_year = 'dayofyear';
    case day_of_month = 'day';
    case day_of_week = 'dayofweek';
    case day_of_week_iso = 'dayofweek_iso';
    case hour = 'hour';
    case minute = 'minute';
    case second = 'second';
    case year_and_month = 'm';
}
