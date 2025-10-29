<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;

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
            Compare::not_equals,
            $column
        );
    }


    /**
     * @param int $start_hour_of_day
     * @param int $end_hour_of_day
     * @param Column $column
     * @return $this
     * @throws InvalidHour
     */
    public function whereHourOfDayBetween(
        int    $start_hour_of_day,
        int    $end_hour_of_day,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateHourRange([$start_hour_of_day, $end_hour_of_day]),
            Field::hour,
            Compare::between,
            $column
        );
    }

    /**
     * @param int $start_hour_of_day
     * @param int $end_hour_of_day
     * @param Column $column
     * @return $this
     * @throws InvalidHour
     */
    public function whereHourOfDayNotBetween(
        int    $start_hour_of_day,
        int    $end_hour_of_day,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateHourRange([$start_hour_of_day, $end_hour_of_day]),
            Field::hour,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $hours
     * @param Column $column
     * @return $this
     * @throws InvalidHour
     * @throws EmptyDateArgument
     */
    public function whereHourOfDayIn(array $hours, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($hours)->validateHourRange($hours),
            Field::hour,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $hours
     * @param Column $column
     * @return $this
     * @throws InvalidHour
     * @throws EmptyDateArgument
     */
    public function whereHourOfDayNotIn(array $hours, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($hours)->validateHourRange($hours),
            Field::hour,
            Compare::not_in,
            $column
        );
    }
}
