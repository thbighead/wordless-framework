<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDTO;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDto\Exceptions\TrySetEmptyDateDto;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\TryBuildEmptyDateSubQuery;
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
