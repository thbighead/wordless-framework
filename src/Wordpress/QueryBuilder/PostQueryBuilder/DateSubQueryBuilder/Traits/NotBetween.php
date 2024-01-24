<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

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

trait NotBetween
{
    /**
     * @param int $start
     * @param int $end
     * @return $this
     * @throws InvalidYear
     */
    public function whereYearNotBetween(int $start, int $end): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_between->value,
            self::KEY_YEAR => [
                $this->validateYearHasFourDigits($start),
                $this->validateYearHasFourDigits($end),
            ],
        ];

        return $this;
    }

    /**
     * @param int $start
     * @param int $end
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonthOfYearNotBetween(int $start, int $end): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_between->value,
            self::KEY_MONTH => [
                $this->validateMonthRange($start),
                $this->validateMonthRange($end),
            ],
        ];

        return $this;
    }

    /**
     * @param int $start
     * @param int $end
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearNotBetween(int $start, int $end): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_between->value,
            self::KEY_WEEK_OF_YEAR => [
                $this->validateWeekOfYearRange($start),
                $this->validateWeekOfYearRange($end),
            ],
        ];

        return $this;
    }

    /**
     * @param int $start
     * @param int $end
     * @return $this
     * @throws InvalidDayOfYear
     * @throws InvalidWeek
     */
    public function whereDayOfYearNotBetween(int $start, int $end): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_between->value,
            self::KEY_DAY_OF_YEAR => [
                $this->validateDayOfYearRange($start),
                $this->validateWeekOfYearRange($end),
            ],
        ];

        return $this;
    }

    /**
     * @param int $start
     * @param int $end
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthNotBetween(int $start, int $end): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_between->value,
            self::KEY_DAY_OF_MONTH => [
                $this->validateDayOfMonthRange($start),
                $this->validateDayOfMonthRange($end),
            ],
        ];

        return $this;
    }

    /**
     * @param int $start
     * @param int $end
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekNotBetween(int $start, int $end): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_between->value,
            self::KEY_DAY_OF_WEEK => [
                $this->validateDayOfWeekRange($start),
                $this->validateDayOfWeekRange($end),
            ],
        ];

        return $this;
    }

    /**
     * @param int $start
     * @param int $end
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoNotBetween(int $start, int $end): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_between->value,
            self::KEY_DAY_OF_WEEK_ISO => [
                $this->validateDayOfWeekRange($start),
                $this->validateDayOfWeekRange($end),
            ],
        ];

        return $this;
    }

    /**
     * @param int $start
     * @param int $end
     * @return $this
     * @throws InvalidHour
     */
    public function whereHourOfDayNotBetween(int $start, int $end): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_between->value,
            self::KEY_HOUR => [
                $this->validateHourRange($start),
                $this->validateHourRange($end),
            ],
        ];

        return $this;
    }

    /**
     * @param int $start
     * @param int $end
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinuteOfHourNotBetween(int $start, int $end): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_between->value,
            self::KEY_MINUTE => [
                $this->validateMinuteRange($start),
                $this->validateMinuteRange($end),
            ],
        ];

        return $this;
    }

    /**
     * @param int $start
     * @param int $end
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondOfMinuteNotBetween(int $start, int $end): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_between->value,
            self::KEY_SECOND => [
                $this->validateSecondRange($start),
                $this->validateSecondRange($end),
            ],
        ];

        return $this;
    }

    /**
     * @param int $start
     * @param int $end
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonthNotBetween(int $start, int $end): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_between->value,
            self::KEY_YEAR_AND_MONTH => [
                $this->validateYearMonth($start),
                $this->validateYearMonth($end),
            ],
        ];

        return $this;
    }
}
