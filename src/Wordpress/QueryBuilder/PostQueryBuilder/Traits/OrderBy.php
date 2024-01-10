<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\Enums\OrderByDirection;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums\ColumnParameter;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Exceptions\InvalidOrderByClause;

trait OrderBy
{
    private const KEY_ORDER_BY = 'orderby';

    /**
     * @param ColumnParameter|ColumnParameter[]|array<ColumnParameter|string,OrderByDirection> $columns
     * @param OrderByDirection $direction ignored if $columns is an associative array
     * @return PostQueryBuilder
     * @throws InvalidOrderByClause
     */
    public function orderBy(
        ColumnParameter|array $columns,
        OrderByDirection      $direction = OrderByDirection::ascending
    ): PostQueryBuilder
    {
        if (!isset($this->arguments[self::KEY_ORDER_BY])) {
            $this->arguments[self::KEY_ORDER_BY] = [];
        }

        if ($columns instanceof ColumnParameter) {
            $columns = $this->formatColumnsFromColumnParameter($columns, $direction);
        }

        $columns = Arr::isAssociative($columns) ?
            $this->validateColumnsAsAssociativeArray($columns) :
            $this->formatColumnsFromList($columns, $direction);

        foreach ($columns as $column => $orderDirection) {
            // ensuring the order of columns (if you ask it again it goes to the end of line)
            if (isset($this->arguments[self::KEY_ORDER_BY][$column])) {
                unset($this->arguments[self::KEY_ORDER_BY][$column]);
            }

            $this->arguments[self::KEY_ORDER_BY][$column] = $orderDirection->value;
        }

        return $this;
    }

    /**
     * @param ColumnParameter $columns
     * @param OrderByDirection $direction
     * @return array<ColumnParameter|string, OrderByDirection>
     */
    private function formatColumnsFromColumnParameter(ColumnParameter $columns, OrderByDirection $direction): array
    {
        return [$columns->value => $direction];
    }

    /**
     * @param array $columns
     * @param OrderByDirection $direction
     * @return array
     * @throws InvalidOrderByClause
     */
    private function formatColumnsFromList(array $columns, OrderByDirection $direction): array
    {
        $order_by = [];

        foreach ($columns as $column) {
            if (!($column instanceof ColumnParameter)) {
                throw new InvalidOrderByClause($column, $direction);
            }

            $order_by[$column->value] = $direction;
        }

        return $order_by;
    }

    /**
     * @param array $columns
     * @return array<ColumnParameter|string,OrderByDirection>
     * @throws InvalidOrderByClause
     */
    private function validateColumnsAsAssociativeArray(array $columns): array
    {
        foreach ($columns as $column => $orderDirection) {
            if (ColumnParameter::tryFrom($column) === null || !($orderDirection instanceof OrderByDirection)) {
                throw new InvalidOrderByClause($column, $orderDirection);
            }
        }

        return $columns;
    }
}
