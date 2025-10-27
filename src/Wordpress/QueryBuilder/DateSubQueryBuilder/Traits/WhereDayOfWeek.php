<?php /** @noinspection DuplicatedCode */

declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

trait WhereDayOfWeek
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
    public function whereDayOfWeekBetween(
        int    $start_day_of_week,
        int    $end_day_of_week,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateDayOfWeekRange([$start_day_of_week, $end_day_of_week]),
            Field::day_of_week,
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
    public function whereDayOfWeekNotBetween(
        int    $start_day_of_week,
        int    $end_day_of_week,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateDayOfWeekRange([$start_day_of_week, $end_day_of_week]),
            Field::day_of_week,
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
    public function whereDayOfWeekIn(array $days_of_week, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($days_of_week)->validateDayOfWeekRange($days_of_week),
            Field::day_of_week,
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
    public function whereDayOfWeekNotIn(array $days_of_week, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($days_of_week)->validateDayOfWeekRange($days_of_week),
            Field::day_of_week,
            Compare::not_in,
            $column
        );
    }
}
