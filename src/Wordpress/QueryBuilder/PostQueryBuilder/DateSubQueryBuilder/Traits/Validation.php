<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYearMonth;

trait Validation
{
    private const MIN_MONTH_OF_YEAR_VALUE = 1;
    private const MAX_MONTH_OF_YEAR_VALUE = 12;
    private const MIN_WEEK_OF_YEAR_VALUE = 1;
    private const MAX_WEEK_OF_YEAR_VALUE = 53;
    private const MIN_DAY_OF_MONTH_VALUE = 1;
    private const MAX_DAY_OF_MONTH_VALUE = 31;
    private const MIN_HOUR_OF_DAY_VALUE = 0;
    private const MAX_HOUR_OF_DAY_VALUE = 23;
    private const MIN_MINUTE_OF_HOUR_VALUE = 0;
    private const MAX_MINUTE_OF_HOUR_VALUE = 59;
    private const MIN_SECOND_OF_MINUTE_VALUE = 0;
    private const MAX_SECOND_OF_MINUTE_VALUE = 59;
    private const MIN_YEAR_VALUE = 1000;
    private const MAX_YEAR_VALUE = 9999;
    private const MIN_DAY_OF_YEAR_VALUE = 1;
    private const MAX_DAY_OF_YEAR_VALUE = 366;
    private const MIN_DAY_OF_WEEK_VALUE = 1;
    private const MAX_DAY_OF_WEEK_VALUE = 7;

    /**
     * @param int|int[] $months
     * @return int|int[]
     * @throws InvalidMonth
     */
    private function validateMonthRange(int|array $months): int|array
    {
        foreach (Arr::wrap($months) as $month) {
            if ($month < self::MIN_MONTH_OF_YEAR_VALUE || $month > self::MAX_MONTH_OF_YEAR_VALUE) {
                throw new InvalidMonth($month);
            }
        }

        return $months;
    }

    /**
     * @param int|int[] $weeks
     * @return int|int[]
     * @throws InvalidWeek
     */
    private function validateWeekOfYearRange(int|array $weeks): int|array
    {
        foreach (Arr::wrap($weeks) as $week) {
            if ($week < self::MIN_WEEK_OF_YEAR_VALUE || $week > self::MAX_WEEK_OF_YEAR_VALUE) {
                throw new InvalidWeek($week);
            }
        }

        return $weeks;
    }

    /**
     * @param int|int[] $days
     * @return int|int[]
     * @throws InvalidDayOfMonth
     */
    private function validateDayOfMonthRange(int|array $days): int|array
    {
        foreach (Arr::wrap($days) as $day) {
            if ($day < self::MIN_DAY_OF_MONTH_VALUE || $day > self::MAX_DAY_OF_MONTH_VALUE) {
                throw new InvalidDayOfMonth($day);
            }
        }

        return $days;
    }

    /**
     * @param int|int[] $hours
     * @return int|int[]
     * @throws InvalidHour
     */
    private function validateHourRange(int|array $hours): int|array
    {
        foreach (Arr::wrap($hours) as $hour) {
            if ($hour < self::MIN_HOUR_OF_DAY_VALUE || $hour > self::MAX_HOUR_OF_DAY_VALUE) {
                throw new InvalidHour($hour);
            }
        }

        return $hours;
    }

    /**
     * @param int|int[] $minutes
     * @return int|int[]
     * @throws InvalidMinute
     */
    private function validateMinuteRange(int|array $minutes): int|array
    {
        foreach (Arr::wrap($minutes) as $minute) {
            if ($minute < self::MIN_MINUTE_OF_HOUR_VALUE || $minute > self::MAX_MINUTE_OF_HOUR_VALUE) {
                throw new InvalidMinute($minute);
            }
        }

        return $minutes;
    }

    /**
     * @param int|int[] $seconds
     * @return int|int[]
     * @throws InvalidSecond
     */
    private function validateSecondRange(int|array $seconds): int|array
    {
        foreach (Arr::wrap($seconds) as $second) {
            if ($second < self::MIN_SECOND_OF_MINUTE_VALUE || $second > self::MAX_SECOND_OF_MINUTE_VALUE) {
                throw new InvalidSecond($second);
            }
        }

        return $seconds;
    }

    /**
     * @param int|int[] $years
     * @return int|int[]
     * @throws InvalidYear
     */
    private function validateYearHasFourDigits(int|array $years): int|array
    {
        foreach (Arr::wrap($years) as $year) {
            if ($year < self::MIN_YEAR_VALUE || $year > self::MAX_YEAR_VALUE) {
                throw new InvalidYear($year);
            }
        }

        return $years;
    }

    /**
     * @param int|int[] $days_of_year
     * @return int|int[]
     * @throws InvalidDayOfYear
     */
    private function validateDayOfYearRange(int|array $days_of_year): int|array
    {
        foreach (Arr::wrap($days_of_year) as $day_of_year) {
            if ($day_of_year < self::MIN_DAY_OF_YEAR_VALUE || $day_of_year > self::MAX_DAY_OF_YEAR_VALUE) {
                throw new InvalidDayOfYear($day_of_year);
            }
        }

        return $days_of_year;
    }

    /**
     * @param int|int[] $days_of_week
     * @return int|int[]
     * @throws InvalidDayOfWeek
     */
    private function validateDayOfWeekRange(int|array $days_of_week): int|array
    {
        foreach (Arr::wrap($days_of_week) as $day_of_week) {
            if ($day_of_week < self::MIN_DAY_OF_WEEK_VALUE || $day_of_week > self::MAX_DAY_OF_WEEK_VALUE) {
                throw new InvalidDayOfWeek($day_of_week);
            }
        }

        return $days_of_week;
    }

    /**
     * @param int|int[] $year_months
     * @return int|int[]
     * @throws InvalidYearMonth
     */
    private function validateYearMonth(int|array $year_months): int|array
    {
        foreach (Arr::wrap($year_months) as $year_month) {
            $year = substr((string)$year_month, 0, 4);
            $month = substr((string)$year_month, 3, 2);

            if (
                ((int)$year < self::MIN_YEAR_VALUE && (int)$year > self::MAX_YEAR_VALUE) ||
                ((int)$month < self::MIN_MONTH_OF_YEAR_VALUE && (int)$month > self::MAX_MONTH_OF_YEAR_VALUE)
            ) {
                throw new InvalidYearMonth((int)$year, (int)$month);
            }
        }

        return $year_months;
    }

    /**
     * @param array $arguments
     * @return $this
     * @throws EmptyDateArgument
     */
    private function validateEmptyArgument(array $arguments): static
    {
        if (empty($arguments)) {
            throw new EmptyDateArgument;
        }

        return $this;
    }
}
