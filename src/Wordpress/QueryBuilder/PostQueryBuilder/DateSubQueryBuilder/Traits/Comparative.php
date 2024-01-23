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

trait Comparative
{
    /**
     * @param int $year
     * @param int ...$years
     * @return $this
     * @throws InvalidYear
     */
    public function whereYearNotIn(int $year, int ...$years): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_in->value,
            self::KEY_YEAR => [
                $this->validateYearHasFourDigits(Arr::prepend($years, $year)),
            ],
        ];

        return $this;
    }

    /**
     * @param int $month
     * @param int ...$months
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonthNotIn(int $month, int ...$months): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_in->value,
            self::KEY_MONTH => [
                $this->validateMonthRange(Arr::prepend($months, $month)),
            ],
        ];

        return $this;
    }

    /**
     * @param int $week
     * @param int ...$weeks
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearNotIn(int $week, int ...$weeks): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_in->value,
            self::KEY_WEEK_OF_YEAR => [
                $this->validateWeekOfYearRange(Arr::prepend($weeks, $week)),
            ],
        ];

        return $this;
    }

    /**
     * @param int $day
     * @param int ...$days
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearNotIn(int $day, int ...$days): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_in->value,
            self::KEY_DAY_OF_YEAR => [
                $this->validateDayOfYearRange(Arr::prepend($days, $day)),
            ],
        ];

        return $this;
    }

    /**
     * @param int $day
     * @param int ...$days
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekNotIn(int $day, int ...$days): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_in->value,
            self::KEY_DAY_OF_WEEK => [
                $this->validateDayOfWeekRange(Arr::prepend($days, $day)),
            ],
        ];

        return $this;
    }
}
