<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;

trait WhereDayOfYear
{
    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearEqual(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearGreaterThan(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearGreaterThanOrEqual(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearLessThan(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearLessThanOrEqual(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearNotEqual(int $day_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange($day_of_year),
            Field::day_of_year,
            Compare::not_equals,
            $column
        );
    }

    /**
     * @param int $start_day_of_year
     * @param int $end_day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearBetween(
        int    $start_day_of_year,
        int    $end_day_of_year,
        Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateDayOfYearRange([$start_day_of_year, $end_day_of_year]),
            Field::day_of_year,
            Compare::between,
            $column
        );
    }

    /**
     * @param int $start_day_of_year
     * @param int $end_day_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYearNotBetween(
        int    $start_day_of_year,
        int    $end_day_of_year,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateDayOfYearRange([$start_day_of_year, $end_day_of_year]),
            Field::day_of_year,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $days_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     * @throws EmptyDateArgument
     */
    public function whereDayOfYearIn(array $days_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($days_of_year)->validateDayOfYearRange($days_of_year),
            Field::day_of_year,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $days_of_year
     * @param Column $column
     * @return $this
     * @throws InvalidDayOfYear
     * @throws EmptyDateArgument
     */
    public function whereDayOfYearNotIn(array $days_of_year, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($days_of_year)->validateDayOfYearRange($days_of_year),
            Field::day_of_year,
            Compare::not_in,
            $column
        );
    }
}
