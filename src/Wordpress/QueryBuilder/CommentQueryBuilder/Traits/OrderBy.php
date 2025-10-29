<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\OrderBy\Enums\ColumnReference;
use Wordless\Wordpress\QueryBuilder\Enums\Direction;

trait OrderBy
{
    private const KEY_ORDER_BY = 'orderby';

    private bool $ordering_by_meta = false;

    public function orderBy(ColumnReference $column, Direction $direction = Direction::ascending): static
    {
        return $this->doOrderBy($column->value, $direction);
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

    public function orderByMeta(string $meta_key, Direction $direction = Direction::ascending): static
    {
        $this->ordering_by_meta = true;

        return $this->doOrderBy($meta_key, $direction);
    }

    private function disableOrderBy(): static
    {
        $this->arguments[self::KEY_ORDER_BY] = [];

        return $this;
    }

    private function doOrderBy(string $column, Direction $direction): static
    {
        $this->arguments[self::KEY_ORDER_BY][$column] = $direction->value;

        return $this;
    }
}
