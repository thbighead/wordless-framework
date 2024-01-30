<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
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
     * @param int $start_week_of_year
     * @param int $end_week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearBetween(
        int    $start_week_of_year,
        int    $end_week_of_year,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateWeekOfYearRange([$start_week_of_year, $end_week_of_year]),
            Field::week_of_year,
            Compare::between,
            $column
        );
    }

    /**
     * @param int $start_week_of_year
     * @param int $end_week_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYearNotBetween(
        int    $start_week_of_year,
        int    $end_week_of_year,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateWeekOfYearRange([$start_week_of_year, $end_week_of_year]),
            Field::week_of_year,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $weeks_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     * @throws EmptyDateArgument
     */
    public function whereWeekOfYearIn(array $weeks_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($weeks_of_year)->validateWeekOfYearRange($weeks_of_year),
            Field::week_of_year,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $weeks_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidWeek
     * @throws EmptyDateArgument
     */
    public function whereWeekOfYearNotIn(array $weeks_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($weeks_of_year)->validateWeekOfYearRange($weeks_of_year),
            Field::week_of_year,
            Compare::not_in,
            $column
        );
    }
}
