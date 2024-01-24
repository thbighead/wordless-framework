<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
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

trait In
{
    /**
     * @param int $year
     * @param int ...$years
     * @return $this
     * @throws InvalidYear
     */
    public function whereYearIn(int $year, int ...$years): static
    {
        $this->arguments[] = [
            'compare' => Compare::in->value,
            self::KEY_YEAR => $this->validateYearHasFourDigits(Arr::prepend($years, $year)),
        ];

        return $this;
    }

    /**
     * @param int $month
     * @param int ...$months
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonthIn(int $month, int ...$months): static
    {
        $this->arguments[] = [
            'compare' => Compare::in->value,
            self::KEY_MONTH => $this->validateMonthRange(Arr::prepend($months, $month)),
        ];

        return $this;
    }

    /**
     * @param int $week
     * @param int ...$weeks
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearIn(int $week, int ...$weeks): static
    {
        $this->arguments[] = [
            'compare' => Compare::in->value,
            self::KEY_WEEK_OF_YEAR => $this->validateWeekOfYearRange(Arr::prepend($weeks, $week)),
        ];

        return $this;
    }

    /**
     * @param int $day
     * @param int ...$days
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearIn(int $day, int ...$days): static
    {
        $this->arguments[] = [
            'compare' => Compare::in->value,
            self::KEY_DAY_OF_YEAR => $this->validateDayOfYearRange(Arr::prepend($days, $day)),
        ];

        return $this;
    }

    /**
     * @param int $day
     * @param int ...$days
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIn(int $day, int ...$days): static
    {
        $this->arguments[] = [
            'compare' => Compare::in->value,
            self::KEY_DAY_OF_WEEK => $this->validateDayOfWeekRange(Arr::prepend($days, $day)),
        ];

        return $this;
    }

    /**
     * @param int $day
     * @param int ...$days
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthIn(int $day, int ...$days): static
    {
        $this->arguments[] = [
            'compare' => Compare::in->value,
            self::KEY_DAY_OF_MONTH => $this->validateDayOfMonthRange(Arr::prepend($days, $day)),
        ];

        return $this;
    }

    /**
     * @param int $day
     * @param int ...$days
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoIn(int $day, int ...$days): static
    {
        $this->arguments[] = [
            'compare' => Compare::in->value,
            self::KEY_DAY_OF_WEEK_ISO => $this->validateDayOfWeekRange(Arr::prepend($days, $day)),
        ];

        return $this;
    }


    /**
     * @param int $hour
     * @param int ...$hours
     * @return $this
     * @throws InvalidHour
     */
    public function whereHourOfDayIn(int $hour, int ...$hours): static
    {
        $this->arguments[] = [
            'compare' => Compare::in->value,
            self::KEY_HOUR => $this->validateHourRange(Arr::prepend($hours, $hour)),
        ];

        return $this;
    }

    /**
     * @param int $minute
     * @param int ...$minutes
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinuteOfHourIn(int $minute, int ...$minutes): static
    {
        $this->arguments[] = [
            'compare' => Compare::in->value,
            self::KEY_MINUTE => $this->validateMinuteRange(Arr::prepend($minutes, $minute)),
        ];

        return $this;
    }

    /**
     * @param int $second
     * @param int ...$seconds
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondOfMinuteIn(int $second, int ...$seconds): static
    {
        $this->arguments[] = [
            'compare' => Compare::in->value,
            self::KEY_SECOND => $this->validateSecondRange(Arr::prepend($seconds, $second)),
        ];

        return $this;
    }

    /**
     * @param int $year_month
     * @param int ...$years_months
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonthIn(int $year_month, int ...$years_months): static
    {
        $this->arguments[] = [
            'compare' => Compare::in->value,
            self::KEY_YEAR_AND_MONTH => $this->validateYearMonth(Arr::prepend($years_months, $year_month)),
        ];

        return $this;
    }
}
