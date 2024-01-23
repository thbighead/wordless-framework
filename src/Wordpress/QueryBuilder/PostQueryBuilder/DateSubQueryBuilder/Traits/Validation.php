<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDay;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeekDay;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeekDayIso;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;

trait Validation
{
    /**
     * @param int $month
     * @return int
     * @throws InvalidMonth
     */
    private function validateMonth(int $month): int
    {
        if ($month < 1 || $month > 12) {
            throw new InvalidMonth($month);
        }

        return $month;
    }

    /**
     * @param int $week
     * @return int
     * @throws InvalidWeek
     */
    private function validateWeek(int $week): int
    {
        if ($week < 0 || $week > 53) {
            throw new InvalidWeek($week);
        }

        return $week;
    }

    /**
     * @param int $day
     * @return int
     * @throws InvalidDay
     */
    private function validateDay(int $day): int
    {
        if ($day < 1 || $day > 30) {
            throw new InvalidDay($day);
        }

        return $day;
    }

    /**
     * @param int $hour
     * @return int
     * @throws InvalidHour
     */
    private function validateHour(int $hour): int
    {
        if ($hour < 0 || $hour > 23) {
            throw new InvalidHour($hour);
        }

        return $hour;
    }

    /**
     * @param int $minute
     * @return int
     * @throws InvalidMinute
     */
    private function validateMinute(int $minute): int
    {
        if ($minute < 0 || $minute > 60) {
            throw new InvalidMinute($minute);
        }

        return $minute;
    }

    /**
     * @param int $second
     * @return int
     * @throws InvalidSecond
     */
    private function validateSecond(int $second): int
    {
        if ($second < 0 || $second > 60) {
            throw new InvalidSecond($second);
        }

        return $second;
    }

    /**
     * @param int $second
     * @return int
     * @throws InvalidSecond
     */
    private function validateYearMonth(int $second): int
    {
        if ($second < 0 || $second > 60) {
            throw new InvalidSecond($second);
        }

        return $second;
    }

    /**
     * @param int $year
     * @return bool
     * @throws InvalidYear
     */
    private function validateFourDigitsRangeYear(int $year): bool
    {
        if ($year < 1000 || $year > 9999) {
            throw new InvalidYear($year);
        }

        return true;
    }

    /**
     * @param int $day_of_year
     * @return int
     * @throws InvalidDayOfYear
     */
    private function validateDayOfYear(int $day_of_year): int
    {
        if ($day_of_year < 1 || $day_of_year > 366) {
            throw new InvalidDayOfYear($day_of_year);
        }

        return $day_of_year;
    }

    /**
     * @param int $day_of_week
     * @return int
     * @throws InvalidWeekDay
     */
    private function validateDayOfWeek(int $day_of_week): int
    {
        if ($day_of_week < 1 || $day_of_week > 7) {
            throw new InvalidWeekDay($day_of_week);
        }

        return $day_of_week;
    }

    /**
     * @param int $day_of_week_iso
     * @return int
     * @throws InvalidWeekDayIso
     */
    private function validateDayOfWeekIso(int $day_of_week_iso): int
    {
        if ($day_of_week_iso < 1 || $day_of_week_iso > 7) {
            throw new InvalidWeekDayIso($day_of_week_iso);
        }

        return $day_of_week_iso;
    }
}
