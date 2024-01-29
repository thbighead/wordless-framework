<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDTO;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\Validation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereDayOfMonth;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereDayOfWeek;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereDayOfWeekIso;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereDayOfYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereHourOfDay;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereMinute;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereMonth;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereSecond;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereWeekOfYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereYear;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits\WhereYearMonth;

class DateSubQueryBuilder extends PostSubQueryBuilder
{
    use Validation;
    use WhereDayOfMonth;
    use WhereDayOfWeek;
    use WhereDayOfWeekIso;
    use WhereDayOfYear;
    use WhereHourOfDay;
    use WhereMinute;
    use WhereMonth;
    use WhereSecond;
    use WhereWeekOfYear;
    use WhereYear;
    use WhereYearMonth;

    final public const ARGUMENT_KEY = 'date_query';
    final public const INCLUSIVE_ARGUMENT = 'inclusive';

    public function __construct(private readonly Relation $relation = Relation::and)
    {
    }

    /**
     * @return array<string|int, string|array<string, string|int|bool>>
     * @throws EmptyQueryBuilderArguments
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
     * @param DateDTO $date
     * @param bool $inclusive
     * @return $this
     */
    public function whereAfter(DateDTO $date, bool $inclusive = false): static
    {
        $this->arguments[] = ['after' => $date->getArguments()];
        $this->arguments[self::INCLUSIVE_ARGUMENT] = $inclusive;

        return $this;
    }

    /**
     * @param DateDTO $date
     * @param bool $inclusive
     * @return $this
     */
    public function whereBefore(DateDTO $date, bool $inclusive = false): static
    {
        $this->arguments[] = ['before' => $date->getArguments()];
        $this->arguments[self::INCLUSIVE_ARGUMENT] = $inclusive;

        return $this;
    }

    /**
     * @param array|int $value
     * @param Field $field
     * @param Compare $compare
     * @param Column $column
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
            Column::KEY => $column->name,
            Compare::KEY => $compare->value,
            $field->name => $value,
        ];

        return $this;
    }

    /**
     * @param DateDTO $dateDTO
     * @param Compare $compare
     * @return $this
     */
    public function whereDate(DateDTO $dateDTO, Compare $compare = Compare::equals): static
    {
        $this->arguments[] = array_merge($dateDTO->getArguments(), [Compare::KEY => $compare->value]);

        return $this;
    }
}
