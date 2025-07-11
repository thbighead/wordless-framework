<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\Enums\Direction;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums\ColumnReference;

trait OrderBy
{
    private const KEY_ORDER_BY = 'orderby';

    /**
     * @param ColumnReference $column
     * @param Direction $direction
     * @return PostQueryBuilder
     */
    public function orderBy(ColumnReference $column, Direction $direction = Direction::ascending): static
    {
        if (!isset($this->arguments[self::KEY_ORDER_BY])) {
            $this->arguments[self::KEY_ORDER_BY] = [];
        }

        $this->arguments[self::KEY_ORDER_BY][$column->value] = $direction->value;

        return $this;
    }

    public function orderByAscending(ColumnReference $column, ColumnReference ...$columns): static
    {
        foreach (Arr::prepend($columns, $column) as $column) {
            $this->orderBy($column);
        }

        return $this;
    }

    public function orderByDescending(ColumnReference $column, ColumnReference ...$columns): static
    {
        foreach (Arr::prepend($columns, $column) as $column) {
            $this->orderBy($column, Direction::descending);
        }

        return $this;
    }

    private function isOrderedBySearchRelevance(): bool
    {
        $first_ordering_column = Arr::getFirstKey($this->arguments[self::KEY_ORDER_BY] ?? []);

        if ($first_ordering_column === null || $first_ordering_column !== ColumnReference::search_relevance->value) {
            return false;
        }

        return $this->arguments[self::KEY_ORDER_BY][$first_ordering_column] === Direction::descending;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function orderBySearchRelevance(): void
    {
        if ($this->isOrderedBySearchRelevance()) {
            return;
        }

        if (empty($this->arguments[self::KEY_ORDER_BY] ?? [])) {
            $this->orderBy(ColumnReference::search_relevance, Direction::descending);

            return;
        }

        // ensuring the relevance will be the very first parameter of ordering
        $this->arguments[self::KEY_ORDER_BY] = [
                ColumnReference::search_relevance->value => Direction::descending,
            ] + $this->arguments[self::KEY_ORDER_BY];
    }
}
