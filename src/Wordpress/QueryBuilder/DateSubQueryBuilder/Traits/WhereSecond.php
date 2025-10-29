<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;

trait WhereSecond
{
    /**
     * @param int $second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondEqual(int $second, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($second),
            Field::second,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondGreaterThan(int $second, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($second),
            Field::second,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondGreaterThanOrEqual(int $second, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($second),
            Field::second,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondLessThan(int $second, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($second),
            Field::second,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondLessThanOrEqual(int $second, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($second),
            Field::second,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondNotEqual(int $second, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($second),
            Field::second,
            Compare::not_equals,
            $column
        );
    }

    /**
     * @param int $start_second
     * @param int $end_second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondBetween(
        int    $start_second,
        int    $end_second,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateSecondRange([$start_second, $end_second]),
            Field::second,
            Compare::between,
            $column
        );
    }

    /**
     * @param int $start_second
     * @param int $end_second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondNotBetween(
        int    $start_second,
        int    $end_second,
        Column $column = Column::post_date
    ): static
    {
        return $this->where(
            $this->validateSecondRange([$start_second, $end_second]),
            Field::second,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $seconds
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     * @throws EmptyDateArgument
     */
    public function whereSecondIn(array $seconds, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($seconds)->validateSecondRange($seconds),
            Field::second,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $seconds
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     * @throws EmptyDateArgument
     */
    public function whereSecondNotIn(array $seconds, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateEmptyArgument($seconds)->validateSecondRange($seconds),
            Field::second,
            Compare::not_in,
            $column
        );
    }
}
