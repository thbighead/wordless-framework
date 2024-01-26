<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;

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
     * @param int[] $second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondBetween(array $second, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($second),
            Field::second,
            Compare::between,
            $column
        );
    }

    /**
     * @param int[] $second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondNotBetween(array $second, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($second),
            Field::second,
            Compare::not_between,
            $column
        );
    }

    /**
     * @param int[] $second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondIn(array $second, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($second),
            Field::second,
            Compare::in,
            $column
        );
    }

    /**
     * @param int[] $second
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondNotIn(array $second, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($second),
            Field::second,
            Compare::not_in,
            $column
        );
    }
}
