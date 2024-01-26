<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;

trait Year
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
            Compare::not_equalt,
            $column
        );
    }
}
