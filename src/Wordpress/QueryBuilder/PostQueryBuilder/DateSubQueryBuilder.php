<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDto;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDto\Exceptions\TrySetEmptyDateDto;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDay;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeekDay;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeekDayIso;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\Validation;

class DateSubQueryBuilder extends PostSubQueryBuilder
{
    use Validation;

    final public const ARGUMENT_KEY = 'date_query';
    protected const KEY_YEAR = 'year';
    protected const KEY_MONTH = 'monthnum';
    protected const KEY_WEEK_OF_YEAR = 'w';
    protected const KEY_DAY_OF_MONTH = 'day';
    protected const KEY_HOUR = 'hour';
    protected const KEY_MINUTE = 'minute';
    protected const KEY_SECOND = 'second';
    protected const KEY_YEAR_AND_MONTH = 'm';
    protected const KEY_DAY_OF_YEAR = 'dayofyear';
    protected const KEY_DAY_OF_WEEK = 'dayofweek';
    protected const KEY_DAY_OF_WEEK_ISO = 'dayofweek_iso';

    public function __construct(private readonly Relation $relation = Relation::and)
    {
    }

    /**
     * @return array<string|int, string|array<string, string|int|bool>>
     */
    protected function buildArguments(): array
    {
        $arguments[Relation::KEY] = $this->relation->value;

        foreach (parent::buildArguments() as $argument) {
            $arguments[] = $argument;
        }

        return $arguments;
    }

    /**
     * @param DateDto $dateDto
     * @return static
     * @throws TrySetEmptyDateDto
     */
    public function whereAfter(DateDto $dateDto): static
    {
        $this->arguments[] = ['after' => $dateDto->getArguments()];

        return $this;
    }

    /**
     * @param DateDto $dateDto
     * @return static
     * @throws TrySetEmptyDateDto
     */
    public function whereBefore(DateDto $dateDto): static
    {
        $this->arguments[] = ['before' => $dateDto->getArguments()];

        return $this;
    }

    /**
     * @param int $year
     * @return $this
     * @throws InvalidYear
     */
    public function whereYear(int $year): static
    {
        $this->arguments[self::KEY_YEAR] = $this->validateFourDigitsRangeYear($year);

        return $this;
    }

    /**
     * @param int $month
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonth(int $month): static
    {
        $this->arguments[self::KEY_MONTH] = $this->validateMonth($month);

        return $this;
    }

    /**
     * @param int $week
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeek(int $week): static
    {
        $this->arguments[self::KEY_WEEK_OF_YEAR] = $this->validateWeek($week);

        return $this;
    }

    /**
     * @param int $day
     * @return $this
     * @throws InvalidDay
     */
    public function whereDay(int $day): static
    {
        $this->arguments[self::KEY_DAY_OF_MONTH] = $this->validateDay($day);

        return $this;
    }

    /**
     * @param int $hour
     * @return $this
     * @throws InvalidHour
     */
    public function whereHour(int $hour): static
    {
        $this->arguments[self::KEY_HOUR] = $this->validateHour($hour);

        return $this;
    }

    /**
     * @param int $minute
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinute(int $minute): static
    {
        $this->arguments[self::KEY_MINUTE] = $this->validateMinute($minute);

        return $this;
    }

    /**
     * @param int $second
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecond(int $second): static
    {
        $this->arguments[self::KEY_SECOND] = $this->validateSecond($second);

        return $this;
    }

    /**
     * @param int $second
     * @return $this
     * @throws InvalidSecond
     */
    public function whereYearMonth(int $second): static
    {
        $this->arguments[self::KEY_YEAR_AND_MONTH] = $this->validateSecond($second);

        return $this;
    }

    /**
     * @param int $day_of_year
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYear(int $day_of_year): static
    {
        $this->arguments[self::KEY_DAY_OF_YEAR] = $this->validateDayOfYear($day_of_year);

        return $this;
    }

    /**
     * @param int $day_of_week
     * @return $this
     * @throws InvalidWeekDay
     */
    public function whereDayOfWeek(int $day_of_week): static
    {
        $this->arguments[self::KEY_DAY_OF_WEEK] = $this->validateDayOfWeek($day_of_week);

        return $this;
    }

    /**
     * @param string $day_of_week_iso
     * @return $this
     * @throws InvalidWeekDayIso
     */
    public function whereDayOfWeekIso(string $day_of_week_iso): static
    {
        $this->arguments[self::KEY_DAY_OF_WEEK_ISO] = $this->validateDayOfWeekIso($day_of_week_iso);

        return $this;
    }
}
