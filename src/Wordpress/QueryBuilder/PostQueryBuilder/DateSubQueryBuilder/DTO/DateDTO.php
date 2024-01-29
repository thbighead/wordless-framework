<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDto\Exceptions\EmptyArguments;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDTO\Exceptions\NotInitializingFromCarbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYearMonth;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\Validation;

class DateDTO
{
    use Validation;

    private array $arguments = [];

    /**
     * @param Carbon|int|null $year
     * @param int|null $month
     * @param int|null $week_of_year
     * @param int|null $day_of_year
     * @param int|null $day_of_month
     * @param int|null $day_of_week
     * @param int|null $day_of_week_iso
     * @param int|null $hour
     * @param int|null $minute
     * @param int|null $second
     * @param int|null $year_and_month
     * @param Column $column
     * @throws InvalidDayOfMonth
     * @throws InvalidDayOfWeek
     * @throws InvalidDayOfYear
     * @throws InvalidHour
     * @throws InvalidMinute
     * @throws InvalidMonth
     * @throws InvalidSecond
     * @throws InvalidWeek
     * @throws InvalidYear
     * @throws InvalidYearMonth
     * @throws EmptyArguments
     */
    public function __construct(
        Carbon|int|null $year = null,
        ?int            $month = null,
        ?int            $week_of_year = null,
        ?int            $day_of_year = null,
        ?int            $day_of_month = null,
        ?int            $day_of_week = null,
        ?int            $day_of_week_iso = null,
        ?int            $hour = null,
        ?int            $minute = null,
        ?int            $second = null,
        ?int            $year_and_month = null,
        Column          $column = Column::post_date
    )
    {
        try {
            $this->initFromCarbon($year);
        } catch (NotInitializingFromCarbon) {
            $this->initFromRawParameters(
                $year,
                $month,
                $week_of_year,
                $day_of_year,
                $day_of_month,
                $day_of_week,
                $day_of_week_iso,
                $hour,
                $minute,
                $second,
                $year_and_month,
            );
        } finally {
            $this->column($column);
        }
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param Carbon|int|null $date
     * @return void
     * @throws InvalidDayOfMonth
     * @throws InvalidDayOfWeek
     * @throws InvalidDayOfYear
     * @throws InvalidHour
     * @throws InvalidMinute
     * @throws InvalidMonth
     * @throws InvalidSecond
     * @throws InvalidWeek
     * @throws InvalidYear
     * @throws NotInitializingFromCarbon
     */
    private function initFromCarbon(Carbon|int|null $date): void
    {
        if (!($date instanceof Carbon)) {
            throw new NotInitializingFromCarbon;
        }

        $this->setYear($date->year)
            ->setMonth($date->month)
            ->setDayOfYear($date->dayOfYear)
            ->setDayOfMonth($date->day)
            ->setDayOfWeek($date->dayOfWeek)
            ->setHour($date->hour)
            ->setMinute($date->minute)
            ->setSecond($date->second)
            ->setWeekOfYear($date->weekOfYear);
    }

    /**
     * @param int|null $year
     * @param int|null $month
     * @param int|null $week_of_year
     * @param int|null $day_of_year
     * @param int|null $day_of_month
     * @param int|null $day_of_week
     * @param int|null $day_of_week_iso
     * @param int|null $hour
     * @param int|null $minute
     * @param int|null $second
     * @param int|null $year_and_month
     * @return void
     * @throws EmptyArguments
     * @throws InvalidDayOfMonth
     * @throws InvalidDayOfWeek
     * @throws InvalidDayOfYear
     * @throws InvalidHour
     * @throws InvalidMinute
     * @throws InvalidMonth
     * @throws InvalidSecond
     * @throws InvalidWeek
     * @throws InvalidYear
     * @throws InvalidYearMonth
     */
    private function initFromRawParameters(
        ?int $year = null,
        ?int $month = null,
        ?int $week_of_year = null,
        ?int $day_of_year = null,
        ?int $day_of_month = null,
        ?int $day_of_week = null,
        ?int $day_of_week_iso = null,
        ?int $hour = null,
        ?int $minute = null,
        ?int $second = null,
        ?int $year_and_month = null
    ): void
    {
        if ($year !== null) {
            $this->setYear($year);
        }

        if ($month !== null) {
            $this->setMonth($month);
        }

        if ($week_of_year !== null) {
            $this->setWeekOfYear($week_of_year);
        }

        if ($day_of_year !== null) {
            $this->setDayOfYear($day_of_year);
        }

        if ($day_of_month !== null) {
            $this->setDayOfMonth($day_of_month);
        }

        if ($day_of_week !== null) {
            $this->setDayOfWeek($day_of_week);
        }

        if ($day_of_week_iso !== null) {
            $this->setDayOfWeekIso($day_of_week_iso);
        }

        if ($hour !== null) {
            $this->setHour($hour);
        }

        if ($minute !== null) {
            $this->setMinute($minute);
        }

        if ($second !== null) {
            $this->setSecond($second);
        }

        if ($year_and_month !== null) {
            $this->setYearMonth($year_and_month);
        }

        if (empty($this->arguments)) {
            throw new EmptyArguments;
        }
    }

    /**
     * @param int $day
     * @return $this
     * @throws InvalidDayOfYear
     */
    private function setDayOfYear(int $day): static
    {
        $this->arguments[Field::day_of_year->value] = $this->validateDayOfYearRange($day);

        return $this;
    }

    /**
     * @param int $day
     * @return $this
     * @throws InvalidDayOfMonth
     */
    private function setDayOfMonth(int $day): static
    {
        $this->arguments[Field::day_of_month->value] = $this->validateDayOfMonthRange($day);

        return $this;
    }

    /**
     * @param int $day
     * @return $this
     * @throws InvalidDayOfWeek
     */
    private function setDayOfWeek(int $day): static
    {
        $this->arguments[Field::day_of_week->value] = $this->validateDayOfWeekRange($day);

        return $this;
    }

    /**
     * @param int $day
     * @return $this
     * @throws InvalidDayOfWeek
     */
    private function setDayOfWeekIso(int $day): static
    {
        $this->arguments[Field::day_of_week_iso->value] = $this->validateDayOfWeekRange($day);

        return $this;
    }

    /**
     * @param int $hour
     * @return $this
     * @throws InvalidHour
     */
    private function setHour(int $hour): static
    {
        $this->arguments[Field::hour->value] = $this->validateHourRange($hour);

        return $this;
    }

    /**
     * @param int $minute
     * @return static
     * @throws InvalidMinute
     */
    private function setMinute(int $minute): static
    {
        $this->arguments[Field::minute->value] = $this->validateMinuteRange($minute);

        return $this;
    }

    /**
     * @param int $month
     * @return static
     * @throws InvalidMonth
     */
    private function setMonth(int $month): static
    {
        $this->arguments[Field::month->value] = $this->validateMonthRange($month);

        return $this;
    }

    /**
     * @param int $second
     * @return static
     * @throws InvalidSecond
     */
    private function setSecond(int $second): static
    {
        $this->arguments[Field::second->value] = $this->validateSecondRange($second);

        return $this;
    }

    /**
     * @param int $week_of_year
     * @return static
     * @throws InvalidWeek
     */
    private function setWeekOfYear(int $week_of_year): static
    {
        $this->arguments[Field::week_of_year->value] = $this->validateWeekOfYearRange($week_of_year);

        return $this;
    }

    /**
     * @param int $year
     * @return static
     * @throws InvalidYear
     */
    private function setYear(int $year): static
    {
        $this->arguments[Field::year->value] = $this->validateYearHasFourDigits($year);

        return $this;
    }

    /**
     * @param int $year_month
     * @return $this
     * @throws InvalidYearMonth
     */
    private function setYearMonth(int $year_month): static
    {
        $this->arguments[Field::year_and_month->value] = $this->validateYearMonth($year_month);

        return $this;
    }

    private function column(Column $column): static
    {
        $this->arguments['column'] = $column->name;

        return $this;
    }
}
