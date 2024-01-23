<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDTO;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDto\Exceptions\TrySetEmptyDateDto;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
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
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\Between;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\Comparative;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\Validation;

class DateSubQueryBuilder extends PostSubQueryBuilder
{
    use Between;
    use Comparative;
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
     * @param DateDTO $dateDto
     * @return static
     * @throws TrySetEmptyDateDto
     */
    public function whereAfter(DateDTO $dateDto): static
    {
        $this->arguments[] = ['after' => $dateDto->getArguments()];

        return $this;
    }

    /**
     * @param DateDTO $dateDto
     * @return static
     * @throws TrySetEmptyDateDto
     */
    public function whereBefore(DateDTO $dateDto): static
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
        $this->arguments[] = [self::KEY_YEAR => $this->validateYearHasFourDigits($year)];

        return $this;
    }

    /**
     * @param int $month
     * @return $this
     * @throws InvalidMonth
     */
    public function whereMonth(int $month): static
    {
        $this->arguments[] = [self::KEY_MONTH => $this->validateMonthRange($month)];

        return $this;
    }

    /**
     * @param int $week
     * @return $this
     * @throws InvalidWeek
     */
    public function whereWeekOfYear(int $week): static
    {
        $this->arguments[] = [self::KEY_WEEK_OF_YEAR => $this->validateWeekOfYearRange($week)];

        return $this;
    }

    /**
     * @param int $day
     * @return $this
     * @throws InvalidDayOfYear
     */
    public function whereDayOfYear(int $day): static
    {
        $this->arguments[self::KEY_DAY_OF_MONTH] = [self::KEY_DAY_OF_MONTH => $this->validateDayOfYearRange($day)];

        return $this;
    }

    /**
     * @param int $hour
     * @return $this
     * @throws InvalidHour
     */
    public function whereHourOfDay(int $hour): static
    {
        $this->arguments[] = [self::KEY_HOUR => $this->validateHourRange($hour)];

        return $this;
    }

    /**
     * @param int $minute
     * @return $this
     * @throws InvalidMinute
     */
    public function whereMinute(int $minute): static
    {
        $this->arguments[] = [self::KEY_MINUTE => $this->validateMinuteRange($minute)];

        return $this;
    }

    /**
     * @param int $second
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecond(int $second): static
    {
        $this->arguments[] = [self::KEY_SECOND => $this->validateSecondRange($second)];

        return $this;
    }

    /**
     * @param int $year_month
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonth(int $year_month): static
    {
        $this->arguments[] = [self::KEY_YEAR_AND_MONTH => $this->validateYearMonth($year_month)];

        return $this;
    }

    /**
     * @param int $day_of_month
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonth(int $day_of_month): static
    {
        $this->arguments[] = [self::KEY_DAY_OF_MONTH => $this->validateDayOfMonthRange($day_of_month)];

        return $this;
    }

    /**
     * @param int $day_of_week
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeek(int $day_of_week): static
    {
        $this->arguments[] = [self::KEY_DAY_OF_WEEK => $this->validateDayOfWeekRange($day_of_week)];

        return $this;
    }

    /**
     * @param string $day_of_week_iso
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIso(string $day_of_week_iso): static
    {
        $this->arguments[] = [self::KEY_DAY_OF_WEEK_ISO => $this->validateDayOfWeekRange($day_of_week_iso)];

        return $this;
    }

    public function whereYearIn(int $day, int ...$days): static
    {
        $this->arguments[] = [
            'compare' => Compare::not_in->value,
            self::KEY_DAY_OF_WEEK => [
                $this->validateDayOfWeekRange(Arr::prepend($days, $day)),
            ],
        ];

        return $this;
    }
}
