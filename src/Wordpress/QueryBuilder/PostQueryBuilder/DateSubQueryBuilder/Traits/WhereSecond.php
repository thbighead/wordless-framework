<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;

trait WhereSecond
{
    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondEqual(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($minute),
            Field::minute,
            Compare::equals,
            $column
        );
    }

    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondGreaterThan(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($minute),
            Field::minute,
            Compare::greater_than,
            $column
        );
    }

    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondGreaterThanOrEqual(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($minute),
            Field::minute,
            Compare::greater_than_or_equals,
            $column
        );
    }

    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondLessThan(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($minute),
            Field::minute,
            Compare::less_than,
            $column
        );
    }

    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondLessThanOrEqual(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($minute),
            Field::minute,
            Compare::less_than_or_equals,
            $column
        );
    }

    /**
     * @param int $minute
     * @param Column $column
     * @return $this
     * @throws InvalidSecond
     */
    public function whereSecondNotEqual(int $minute, Column $column = Column::post_date): static
    {
        return $this->where(
            $this->validateSecondRange($minute),
            Field::minute,
            Compare::not_equalt,
            $column
        );
    }
}
