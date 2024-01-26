<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

trait DayOfWeek
{
    /**
     * @param int $day_of_week
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekEqual(int $day_of_week, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week),
            Field::day_of_week,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $day_of_week
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekGreaterThan(int $day_of_week, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week),
            Field::day_of_week,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $day_of_week
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekGreaterThanOrEqual(int $day_of_week, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week),
            Field::day_of_week,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $day_of_week
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekLessThan(int $day_of_week, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week),
            Field::day_of_week,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $day_of_week
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekLessThanOrEqual(int $day_of_week, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week),
            Field::day_of_week,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $day_of_week
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekNotEqual(int $day_of_week, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week),
            Field::day_of_week,
            Compare::not_equalt,
            $column
        );
    }
}
