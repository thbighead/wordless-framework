<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDTO;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDto\Exceptions\TrySetEmptyDateDto;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\LogicCompare;
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
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\TryBuildEmptyDateSubQuery;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\Between;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\DayOfMonth;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\DayOfWeek;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\DayOfYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\In;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\Month;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\NotBetween;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\NotIn;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\Validation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WeekOfYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereHourOfDay;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereMinute;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereSecond;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\Year;

class DateSubQueryBuilder extends PostSubQueryBuilder
{
    use Between;
    use DayOfYear;
    use DayOfMonth;
    use DayOfWeek;
    use WhereHourOfDay;
    use WhereMinute;
    use WhereSecond;
    use In;
    use NotBetween;
    use NotIn;
    use Validation;
    use Year;
    use Month;
    use WeekOfYear;

    final public const ARGUMENT_KEY = 'date_query';
    protected const KEY_COMPARE = 'compare';

    public function __construct(private readonly Relation $relation = Relation::and)
    {
    }

    /**
     * @return array<string|int, string|array<string, string|int|bool>>
     * @throws TryBuildEmptyDateSubQuery
     */
    protected function buildArguments(): array
    {
        $arguments[Relation::KEY] = $this->relation->value;

        foreach (parent::buildArguments() as $argument) {
            $arguments[] = $argument;
        }

        return $this->isArgumentsEmpty($arguments);
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
     * @param Column $column
     * @param Compare $compare
     * @param Field $field
     * @param array|int $value
     * @return $this
     */
    public function where(
        array|int $value,
        Field     $field,
        Compare   $compare,
        Column    $column
    ): static
    {
        $this->arguments[] = [
            'column' => $column->name,
            'compare' => $compare->value,
            $field->name => $value
        ];

        return $this;
    }

    /**
     * @param int $second
     * @param LogicCompare $logicCompare
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecond(int $second, LogicCompare $logicCompare = LogicCompare::equals): static
    {
        $this->arguments[] = [
            self::KEY_SECOND => $this->validateSecondRange($second),
            self::KEY_COMPARE => $logicCompare->value
        ];

        return $this;
    }

    /**
     * @param int $year_month
     * @param LogicCompare $logicCompare
     * @return $this
     * @throws InvalidYearMonth
     */
    public function whereYearMonth(int $year_month, LogicCompare $logicCompare = LogicCompare::equals): static
    {
        $this->arguments[] = [
            self::KEY_YEAR_AND_MONTH => $this->validateYearMonth($year_month),
            self::KEY_COMPARE => $logicCompare->value
        ];

        return $this;
    }

    /**
     * @param int $day_of_month
     * @param LogicCompare $logicCompare
     * @return $this
     * @throws InvalidDayOfMonth
     */
    public function whereDayOfMonth(int $day_of_month, LogicCompare $logicCompare = LogicCompare::equals): static
    {
        $this->arguments[] = [
            self::KEY_DAY_OF_MONTH => $this->validateDayOfMonthRange($day_of_month),
            self::KEY_COMPARE => $logicCompare->value
        ];

        return $this;
    }

    /**
     * @param int $day_of_week
     * @param LogicCompare $logicCompare
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeek(int $day_of_week, LogicCompare $logicCompare = LogicCompare::equals): static
    {
        $this->arguments[] = [
            self::KEY_DAY_OF_WEEK => $this->validateDayOfWeekRange($day_of_week),
            self::KEY_COMPARE => $logicCompare->value
        ];

        return $this;
    }

    /**
     * @param string $day_of_week_iso
     * @param LogicCompare $logicCompare
     * @return $this
     * @throws InvalidDayOfWeek
     */
    public function whereDayOfWeekIso(
        string       $day_of_week_iso,
        LogicCompare $logicCompare = LogicCompare::equals
    ): static
    {
        $this->arguments[] = [
            self::KEY_DAY_OF_WEEK_ISO => $this->validateDayOfWeekRange($day_of_week_iso),
            self::KEY_COMPARE => $logicCompare->value
        ];

        return $this;
    }

    public function inclusive(bool $inclusive = true): static
    {
        $this->arguments['inclusive'] = $inclusive;

        return $this;
    }

    /**
     * @param array $arguments
     * @return array
     * @throws TryBuildEmptyDateSubQuery
     */
    private function isArgumentsEmpty(array $arguments): array
    {
        // Validate if arguments has nothing or only default relation key.
        if (count($arguments) < 2) {
            throw new TryBuildEmptyDateSubQuery;
        }

        return $arguments;
    }
}
