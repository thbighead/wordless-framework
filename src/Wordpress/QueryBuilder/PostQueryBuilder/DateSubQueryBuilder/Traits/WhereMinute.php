<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;

trait WhereMinute
{
    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinuteEqual(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMinuteRange($minute),
            Field::minute,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinuteGreaterThan(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMinuteRange($minute),
            Field::minute,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinuteGreaterThanOrEqual(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMinuteRange($minute),
            Field::minute,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinuteLessThan(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMinuteRange($minute),
            Field::minute,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinuteLessThanOrEqual(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMinuteRange($minute),
            Field::minute,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinuteNotEqual(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateMinuteRange($minute),
            Field::minute,
            Compare::not_equals,
            $column
        );
    }

    /**
     * @param int $start_minute
     * @param int $end_minute
     * @param Column $column
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinuteBetween(
        int    $start_minute,
        int    $end_minute,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateMinuteRange([$start_minute, $end_minute]),
            Field::minute,
            Compare::between,
            $column
        );
    }

    /**
     * @param int $start_minute
     * @param int $end_minute
     * @param Column $column
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinuteNotBetween(
        int    $start_minute,
        int    $end_minute,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateMinuteRange([$start_minute, $end_minute]),
            Field::minute,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $minutes
     * @param Column $column
     * @return $this
     * @throws InvalidMinute
     * @throws EmptyDateArgument
     */
    public function whereMinuteIn(array $minutes, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($minutes)->validateMinuteRange($minutes),
            Field::minute,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $minutes
     * @param Column $column
     * @return $this
     * @throws InvalidMinute
     * @throws EmptyDateArgument
     */
    public function whereMinuteNotIn(array $minutes, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($minutes)->validateMinuteRange($minutes),
            Field::minute,
            Compare::not_in,
            $column
        );
    }
}
