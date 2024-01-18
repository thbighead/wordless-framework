<?php

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder;

abstract class DateSubQueryBuilder extends QueryBuilder
{
    protected const KEY_YEAR = 'year';
    protected const KEY_MONTH = 'monthnum';
    protected const KEY_WEEK_OF_YEAR = 'w';
    protected const KEY_DAY_OF_MONTH = 'day';
    protected const KEY_HOUR = 'hour';

    protected function fromYear(int $year)
    {

    }
}
