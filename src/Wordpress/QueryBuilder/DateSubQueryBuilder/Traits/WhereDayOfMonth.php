<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;

trait WhereDayOfMonth
{
    /**
     * @param int $day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthEqual(int $day_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfMonthRange($day_of_month),
            Field::day_of_month,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthGreaterThan(int $day_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfMonthRange($day_of_month),
            Field::day_of_month,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthGreaterThanOrEqual(int $day_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfMonthRange($day_of_month),
            Field::day_of_month,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthLessThan(int $day_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfMonthRange($day_of_month),
            Field::day_of_month,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthLessThanOrEqual(int $day_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfMonthRange($day_of_month),
            Field::day_of_month,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthNotEqual(int $day_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfMonthRange($day_of_month),
            Field::day_of_month,
            Compare::not_equals,
            $column
        );
    }

    /**
     * @param int $start_day_of_month
     * @param int $end_day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthBetween(
        int    $start_day_of_month,
        int    $end_day_of_month,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateDayOfMonthRange([$start_day_of_month, $end_day_of_month]),
            Field::day_of_month,
            Compare::between,
            $column
        );
    }

    /**
     * @param int $start_day_of_month
     * @param int $end_day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthNotBetween(
        int    $start_day_of_month,
        int    $end_day_of_month,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateDayOfMonthRange([$start_day_of_month, $end_day_of_month]),
            Field::day_of_month,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $days_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     * @throws EmptyDateArgument
     */
    public function whereDayOfMonthIn(array $days_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($days_of_month)->validateDayOfMonthRange($days_of_month),
            Field::day_of_month,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $days_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     * @throws EmptyDateArgument
     */
    public function whereDayOfMonthNotIn(array $days_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($days_of_month)->validateDayOfMonthRange($days_of_month),
            Field::day_of_month,
            Compare::not_in,
            $column
        );
    }
}
