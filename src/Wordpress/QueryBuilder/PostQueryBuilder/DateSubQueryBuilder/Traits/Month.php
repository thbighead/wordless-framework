<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;

trait Month
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
            Compare::not_equalt,
            $column
        );
    }
}
