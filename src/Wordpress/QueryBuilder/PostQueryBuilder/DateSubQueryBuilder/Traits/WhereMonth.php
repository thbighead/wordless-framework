<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;

trait WhereMonth
{
    /**
     * @param int $month
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonthEqual(int $month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMonthRange($month),
            Field::month,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $month
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonthGreaterThan(int $month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMonthRange($month),
            Field::month,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $month
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonthGreaterThanOrEqual(int $month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMonthRange($month),
            Field::month,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $month
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonthLessThan(int $month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMonthRange($month),
            Field::month,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $month
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonthLessThanOrEqual(int $month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMonthRange($month),
            Field::month,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $month
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonthNotEqual(int $month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMonthRange($month),
            Field::month,
            Compare::not_equals,
            $column
        );
    }

    /**
     * @param int[] $months
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     * @throws EmptyDateArgument
     */
    public function whereMonthBetween(array $months, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($months)->validateMonthRange($months),
            Field::month,
            Compare::between,
            $column
        );
    }

    /**
     * @param int[] $months
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     * @throws EmptyDateArgument
     */
    public function whereMonthNotBetween(array $months, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($months)->validateMonthRange($months),
            Field::month,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param array $months
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     * @throws EmptyDateArgument
     */
    public function whereMonthIn(array $months, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($months)->validateMonthRange($months),
            Field::month,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $months
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     * @throws EmptyDateArgument
     */
    public function whereMonthNotIn(array $months, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($months)->validateMonthRange($months),
            Field::month,
            Compare::not_in,
            $column
        );
    }
}
