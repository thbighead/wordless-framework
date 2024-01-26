<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;

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
     * @param int[] $day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthBetween(array $day_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfMonthRange($day_of_month),
            Field::day_of_month,
            Compare::between,
            $column
        );
    }

    /**
     * @param int[] $day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthNotBetween(array $day_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfMonthRange($day_of_month),
            Field::day_of_month,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthIn(array $day_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfMonthRange($day_of_month),
            Field::day_of_month,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $day_of_month
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonthNotIn(array $day_of_month, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfMonthRange($day_of_month),
            Field::day_of_month,
            Compare::not_in,
            $column
        );
    }
}
