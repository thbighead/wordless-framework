<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDto\Exceptions\TrySetEmptyDateDto;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDay;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\Validation;

class DateDto
{
    use Validation;

    private array $arguments = [];

    /**
     * @param Carbon|null $date
     * @throws InvalidDay
     * @throws InvalidHour
     * @throws InvalidMinute
     * @throws InvalidMonth
     * @throws InvalidSecond
     * @throws InvalidWeek
     * @throws InvalidYear
     */
    public function __construct(?Carbon $date = null)
    {
        if ($date instanceof Carbon) {
            $this->setYear($date->year);
            $this->setMonth($date->month);
            $this->setDay($date->day);
            $this->setHour($date->hour);
            $this->setMinute($date->minute);
            $this->setSecond($date->second);
            $this->setWeekOfYear($date->weekOfYear);
        }
    }

    /**
     * @param int $day
     * @return $this
     * @throws InvalidDay
     */
    public function setDay(int $day): static
    {
        $this->arguments['day'] = $this->validateDay($day);

        return $this;
    }

    /**
     * @param int $hour
     * @return $this
     * @throws InvalidHour
     */
    public function setHour(int $hour): static
    {
        $this->arguments['hour'] = $this->validateHour($hour);

        return $this;
    }

    /**
     * @param int $minute
     * @return static
     * @throws InvalidMinute
     */
    public function setMinute(int $minute): static
    {
        $this->arguments['minute'] = $this->validateMinute($minute);

        return $this;
    }

    /**
     * @param int $month
     * @return static
     * @throws InvalidMonth
     */
    public function setMonth(int $month): static
    {
        $this->arguments['month'] = $this->validateMonth($month);

        return $this;
    }

    /**
     * @param int $second
     * @return static
     * @throws InvalidSecond
     */
    public function setSecond(int $second): static
    {
        $this->arguments['second'] = $this->validateSecond($second);

        return $this;
    }

    /**
     * @param int $week_of_year
     * @return static
     * @throws InvalidWeek
     */
    public function setWeekOfYear(int $week_of_year): static
    {
        $this->arguments['week_of_year'] = $this->validateWeek($week_of_year);

        return $this;
    }

    /**
     * @param int $year
     * @return static
     * @throws InvalidYear
     */
    public function setYear(int $year): static
    {
        $this->arguments['year'] = $this->validateFourDigitsRangeYear($year);

        return $this;
    }

    public function inclusive(bool $inclusive = true): static
    {
        $this->arguments['inclusive'] = $inclusive;

        return $this;
    }

    public function column(string $column): static
    {
        $this->arguments['column'] = $column;

        return $this;
    }

    public function compare(Compare $compare): static
    {
        $this->arguments['compare'] = $compare->value;

        return $this;
    }

    /**
     * @return array
     * @throws TrySetEmptyDateDto
     */
    public function getArguments(): array
    {
        if (empty($this->arguments)) {
            throw new TrySetEmptyDateDto;
        }

        return $this->arguments;
    }
}
