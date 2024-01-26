<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;

trait DayOfYear
{
    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearEqual(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearGreaterThan(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearGreaterThanOrEqual(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearLessThan(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearLessThanOrEqual(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearNotEqual(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::not_equalt,
            $column
        );
    }
}
