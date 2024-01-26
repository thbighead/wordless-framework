<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;

trait WhereWeekOfYear
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
            Compare::not_equals,
            $column
        );
    }

    /**
     * @param int[] $week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearBetween(array $week_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateWeekOfYearRange($week_of_year),
            Field::week_of_year,
            Compare::between,
            $column
        );
    }

    /**
     * @param int[] $week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearNotBetween(array $week_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateWeekOfYearRange($week_of_year),
            Field::week_of_year,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearIn(array $week_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateWeekOfYearRange($week_of_year),
            Field::week_of_year,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearNotIn(array $week_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateWeekOfYearRange($week_of_year),
            Field::week_of_year,
            Compare::not_in,
            $column
        );
    }
}
