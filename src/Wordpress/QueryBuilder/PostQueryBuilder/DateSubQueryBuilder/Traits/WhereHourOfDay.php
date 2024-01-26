<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;

trait WhereHourOfDay
{
    /**
     * @param int $hour
     * @param Column $column
     * @return $this
     * @throws InvalidHour
     */
    public function whereHourOfDayEqual(int $hour, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateHourRange($hour),
            Field::hour,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $hour
     * @param Column $column
     * @return $this
     * @throws InvalidHour
     */
    public function whereHourOfDayGreaterThan(int $hour, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateHourRange($hour),
            Field::hour,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $hour
     * @param Column $column
     * @return $this
     * @throws InvalidHour
     */
    public function whereHourOfDayGreaterThanOrEqual(int $hour, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateHourRange($hour),
            Field::hour,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $hour
     * @param Column $column
     * @return $this
     * @throws InvalidHour
     */
    public function whereHourOfDayLessThan(int $hour, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateHourRange($hour),
            Field::hour,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $hour
     * @param Column $column
     * @return $this
     * @throws InvalidHour
     */
    public function whereHourOfDayLessThanOrEqual(int $hour, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateHourRange($hour),
            Field::hour,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $hour
     * @param Column $column
     * @return $this
     * @throws InvalidHour
     */
    public function whereHourOfDayNotEqual(int $hour, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateHourRange($hour),
            Field::hour,
            Compare::not_equalt,
            $column
        );
    }
}
