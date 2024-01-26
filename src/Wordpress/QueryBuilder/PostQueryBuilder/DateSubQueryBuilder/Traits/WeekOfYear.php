<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;

trait WeekOfYear
{
    /**
     * @param int $week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearEqual(int $week_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateWeekOfYearRange($week_of_year),
            Field::week_of_year,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearGreaterThan(int $week_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateWeekOfYearRange($week_of_year),
            Field::week_of_year,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearGreaterThanOrEqual(int $week_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateWeekOfYearRange($week_of_year),
            Field::week_of_year,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearLessThan(int $week_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateWeekOfYearRange($week_of_year),
            Field::week_of_year,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearLessThanOrEqual(int $week_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateWeekOfYearRange($week_of_year),
            Field::week_of_year,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearNotEqual(int $week_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateWeekOfYearRange($week_of_year),
            Field::week_of_year,
            Compare::not_equalt,
            $column
        );
    }
}
