<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

trait WhereDayOfWeekIso
{
    /**
     * @param int $day_of_week_iso
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoEqual(int $day_of_week_iso, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week_iso),
            Field::day_of_week_iso,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $day_of_week_iso
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoGreaterThan(int $day_of_week_iso, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week_iso),
            Field::day_of_week_iso,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $day_of_week_iso
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoGreaterThanOrEqual(int $day_of_week_iso, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week_iso),
            Field::day_of_week_iso,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $day_of_week_iso
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoLessThan(int $day_of_week_iso, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week_iso),
            Field::day_of_week_iso,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $day_of_week_iso
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoLessThanOrEqual(int $day_of_week_iso, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week_iso),
            Field::day_of_week_iso,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $day_of_week_iso
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoNotEqual(int $day_of_week_iso, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week_iso),
            Field::day_of_week_iso,
            Compare::not_equals,
            $column
        );
    }

    /**
     * @param int[] $day_of_week_iso
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoBetween(array $day_of_week_iso, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week_iso),
            Field::day_of_week_iso,
            Compare::between,
            $column
        );
    }

    /**
     * @param int[] $day_of_week_iso
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoNotBetween(array $day_of_week_iso, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week_iso),
            Field::day_of_week_iso,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $day_of_week_iso
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoIn(array $day_of_week_iso, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week_iso),
            Field::day_of_week_iso,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $day_of_week_iso
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoNotIn(array $day_of_week_iso, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfWeekRange($day_of_week_iso),
            Field::day_of_week_iso,
            Compare::not_in,
            $column
        );
    }
}
