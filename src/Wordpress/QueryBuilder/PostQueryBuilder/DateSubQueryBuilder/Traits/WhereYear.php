<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;

trait WhereYear
{
    /**
     * @param int $year
     * @param Column $column
     * @return $this
     * @throws InvalidYear
     */
    public function whereYearEqual(int $year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearHasFourDigits($year),
            Field::year,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $year
     * @param Column $column
     * @return $this
     * @throws InvalidYear
     */
    public function whereYearGreaterThan(int $year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearHasFourDigits($year),
            Field::year,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $year
     * @param Column $column
     * @return $this
     * @throws InvalidYear
     */
    public function whereYearGreaterThanOrEqual(int $year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearHasFourDigits($year),
            Field::year,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $year
     * @param Column $column
     * @return $this
     * @throws InvalidYear
     */
    public function whereYearLessThan(int $year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearHasFourDigits($year),
            Field::year,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $year
     * @param Column $column
     * @return $this
     * @throws InvalidYear
     */
    public function whereYearLessThanOrEqual(int $year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearHasFourDigits($year),
            Field::year,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $year
     * @param Column $column
     * @return $this
     * @throws InvalidYear
     */
    public function whereYearNotEqual(int $year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearHasFourDigits($year),
            Field::year,
            Compare::not_equals,
            $column
        );
    }

    /**
     * @param int $start_year
     * @param int $end_year
     * @param Column $column
     * @return $this
     * @throws InvalidYear
     */
    public function whereYearBetween(
        int    $start_year,
        int    $end_year,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateYearHasFourDigits([$start_year, $end_year]),
            Field::year,
            Compare::between,
            $column
        );
    }

    /**
     * @param int $start_year
     * @param int $end_year
     * @param Column $column
     * @return $this
     * @throws InvalidYear
     */
    public function whereYearNotBetween(
        int    $start_year,
        int    $end_year,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateYearHasFourDigits([$start_year, $end_year]),
            Field::year,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $years
     * @param Column $column
     * @return $this
     * @throws InvalidYear
     * @throws EmptyDateArgument
     */
    public function whereYearIn(array $years, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($years)->validateYearHasFourDigits($years),
            Field::year,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $years
     * @param Column $column
     * @return $this
     * @throws InvalidYear
     * @throws EmptyDateArgument
     */
    public function whereYearNotIn(array $years, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($years)->validateYearHasFourDigits($years),
            Field::year,
            Compare::not_in,
            $column
        );
    }
}
