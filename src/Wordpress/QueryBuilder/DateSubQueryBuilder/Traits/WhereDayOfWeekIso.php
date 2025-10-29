<?php /** @noinspection DuplicatedCode */

declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

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
     * @param int $start_day_of_week
     * @param int $end_day_of_week
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoBetween(
        int    $start_day_of_week,
        int    $end_day_of_week,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateDayOfWeekRange([$start_day_of_week, $end_day_of_week]),
            Field::day_of_week_iso,
            Compare::between,
            $column
        );
    }

    /**
     * @param int $start_day_of_week
     * @param int $end_day_of_week
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIsoNotBetween(
        int    $start_day_of_week,
        int    $end_day_of_week,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateDayOfWeekRange([$start_day_of_week, $end_day_of_week]),
            Field::day_of_week_iso,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $days_of_week
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     * @throws EmptyDateArgument
     */
    public function whereDayOfWeekIsoIn(array $days_of_week, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($days_of_week)->validateDayOfWeekRange($days_of_week),
            Field::day_of_week_iso,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $days_of_week
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfWeek
     * @throws EmptyDateArgument
     */
    public function whereDayOfWeekIsoNotIn(array $days_of_week, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($days_of_week)->validateDayOfWeekRange($days_of_week),
            Field::day_of_week_iso,
            Compare::not_in,
            $column
        );
    }
}
