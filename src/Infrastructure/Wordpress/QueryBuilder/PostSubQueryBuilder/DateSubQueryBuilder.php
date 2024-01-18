<?php

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder;

use Carbon\Carbon;
use DateTime;
use Wordless\Infrastructure\Wordpress\QueryBuilder;

abstract class DateSubQueryBuilder extends QueryBuilder
{
    protected const KEY_YEAR = 'year';
    protected const KEY_MONTH = 'monthnum';
    protected const KEY_WEEK_OF_YEAR = 'w';
    protected const KEY_DAY_OF_MONTH = 'day';
    protected const KEY_HOUR = 'hour';
    protected const KEY_MINUTE = 'minute';
    protected const KEY_SECOND = 'second';
    protected const KEY_YEAR_AND_MONTH = 'm';

    protected function fromYear(Carbon|DateTime|int $year)
    {
        if ($year instanceof Carbon) {
//            $this->arguments[self::KEY_YEAR] = $year->
        }
    }
}
