<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYearMonth;

trait WhereYearMonth
{
    /**
     * @param int $year_month
     * @param Column $column
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonthEqual(int $year_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearMonth($year_month),
            Field::year_and_month,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $year_month
     * @param Column $column
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonthGreaterThan(int $year_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearMonth($year_month),
            Field::year_and_month,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $year_month
     * @param Column $column
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonthGreaterThanOrEqual(int $year_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearMonth($year_month),
            Field::year_and_month,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $year_month
     * @param Column $column
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonthLessThan(int $year_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearMonth($year_month),
            Field::year_and_month,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $year_month
     * @param Column $column
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonthLessThanOrEqual(int $year_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearMonth($year_month),
            Field::year_and_month,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $year_month
     * @param Column $column
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonthNotEqual(int $year_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateYearMonth($year_month),
            Field::year_and_month,
            Compare::not_equals,
            $column
        );
    }

    /**
     * @param int $start_year_month
     * @param int $end_year_month
     * @param Column $column
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonthBetween(
        int    $start_year_month,
        int    $end_year_month,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateYearMonth([$start_year_month, $end_year_month]),
            Field::year_and_month,
            Compare::between,
            $column
        );
    }

    /**
     * @param int $start_year_month
     * @param int $end_year_month
     * @param Column $column
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonthNotBetween(
        int    $start_year_month,
        int    $end_year_month,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateYearMonth([$start_year_month, $end_year_month]),
            Field::year_and_month,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $year_month
     * @param Column $column
     * @return $this
     * @throws InvalidYearMonth
     * @throws EmptyDateArgument
     */
    public function whereYearMonthIn(array $year_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($year_month)->validateYearMonth($year_month),
            Field::year_and_month,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $year_month
     * @param Column $column
     * @return $this
     * @throws InvalidYearMonth
     * @throws EmptyDateArgument
     */
    public function whereYearMonthNotIn(array $year_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($year_month)->validateYearMonth($year_month),
            Field::year_and_month,
            Compare::not_in,
            $column
        );
    }
}
