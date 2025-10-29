<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\DTO;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\DTO\DateDTO\Exceptions\EmptyArguments;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\DTO\DateDTO\Exceptions\NotInitializingFromCarbon;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYearMonth;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Traits\Validation;

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
        $this->setYear($year)
            ->setMonth($month)
            ->setWeekOfYear($week_of_year)
            ->setDayOfYear($day_of_year)
            ->setDayOfMonth($day_of_month)
            ->setDayOfWeek($day_of_week)
            ->setDayOfWeekIso($day_of_week_iso)
            ->setHour($hour)
            ->setMinute($minute)
            ->setSecond($second)
            ->setYearMonth($year_and_month);

        if (empty($this->arguments)) {
            throw new EmptyArguments;
        }
    }

    /**
     * @param int|null $day
     * @return $this
     * @throws InvalidDayOfYear
     */
    private function setDayOfYear(?int $day): static
    {
        if ($day !== null) {
            $this->arguments[Field::day_of_year->value] = $this->validateDayOfYearRange($day);
        }

        return $this;
    }

    /**
     * @param int|null $day
     * @return $this
     * @throws InvalidDayOfMonth
     */
    private function setDayOfMonth(?int $day): static
    {
        if ($day !== null) {
            $this->arguments[Field::day_of_month->value] = $this->validateDayOfMonthRange($day);
        }

        return $this;
    }

    /**
     * @param int|null $day
     * @return $this
     * @throws InvalidDayOfWeek
     */
    private function setDayOfWeek(?int $day): static
    {
        if ($day !== null) {
            $this->arguments[Field::day_of_week->value] = $this->validateDayOfWeekRange($day);
        }

        return $this;
    }

    /**
     * @param int|null $day
     * @return $this
     * @throws InvalidDayOfWeek
     */
    private function setDayOfWeekIso(?int $day): static
    {
        if ($day !== null) {
            $this->arguments[Field::day_of_week_iso->value] = $this->validateDayOfWeekRange($day);
        }

        return $this;
    }

    /**
     * @param int|null $hour
     * @return $this
     * @throws InvalidHour
     */
    private function setHour(?int $hour): static
    {
        if ($hour !== null) {
            $this->arguments[Field::hour->value] = $this->validateHourRange($hour);
        }

        return $this;
    }

    /**
     * @param int|null $minute
     * @return $this
     * @throws InvalidMinute
     */
    private function setMinute(?int $minute): static
    {
        if ($minute !== null) {
            $this->arguments[Field::minute->value] = $this->validateMinuteRange($minute);
        }

        return $this;
    }

    /**
     * @param int|null $month
     * @return $this
     * @throws InvalidMonth
     */
    private function setMonth(?int $month): static
    {
        if ($month !== null) {
            $this->arguments[Field::month->value] = $this->validateMonthRange($month);
        }

        return $this;
    }

    /**
     * @param int|null $second
     * @return $this
     * @throws InvalidSecond
     */
    private function setSecond(?int $second): static
    {
        if ($second !== null) {
            $this->arguments[Field::second->value] = $this->validateSecondRange($second);
        }

        return $this;
    }

    /**
     * @param int|null $week_of_year
     * @return $this
     * @throws InvalidWeek
     */
    private function setWeekOfYear(?int $week_of_year): static
    {
        if ($week_of_year !== null) {
            $this->arguments[Field::week_of_year->value] = $this->validateWeekOfYearRange($week_of_year);
        }

        return $this;
    }

    /**
     * @param int|null $year
     * @return $this
     * @throws InvalidYear
     */
    private function setYear(?int $year): static
    {
        if ($year !== null) {
            $this->arguments[Field::year->value] = $this->validateYearHasFourDigits($year);
        }

        return $this;
    }

    /**
     * @param int|null $year_month
     * @return $this
     * @throws InvalidYearMonth
     */
    private function setYearMonth(?int $year_month): static
    {
        if ($year_month !== null) {
            $this->arguments[Field::year_and_month->value] = $this->validateYearMonth($year_month);
        }

        return $this;
    }

    private function column(Column $column): static
    {
        $this->arguments['column'] = $column->name;

        return $this;
    }
}
