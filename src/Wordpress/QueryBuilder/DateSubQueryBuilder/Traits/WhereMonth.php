<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;

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
     * @param int $start_month
     * @param int $end_month
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonthBetween(
        int    $start_month,
        int    $end_month,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateMonthRange([$start_month, $end_month]),
            Field::month,
            Compare::between,
            $column
        );
    }

    /**
     * @param int $start_month
     * @param int $end_month
     * @param Column $column
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonthNotBetween(
        int    $start_month,
        int    $end_month,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateMonthRange([$start_month, $end_month]),
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
