<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\Enums\Direction;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\OrderBy\Enums\FieldReference;

trait OrderBy
{
    private const DIRECTION_KEY = 'order';
    private const ORDER_BY_KEY = 'orderby';

    public function orderBy(FieldReference $field, Direction $direction = Direction::ascending): static
    {
        return $this->doOrderBy($field->value, $direction);
    }

    public function orderByAscending(FieldReference|string $field, FieldReference|string ...$fields): static
    {
        foreach (Arr::prepend($fields, $field) as $field) {
            match (true) {
                $field instanceof FieldReference => $this->orderBy($field),
                is_string($field) => $this->orderByMeta($field),
            };
        }

        return $this;
    }

    public function orderByDescending(FieldReference|string $field, FieldReference|string ...$fields): static
    {
        foreach (Arr::prepend($fields, $field) as $field) {
            match (true) {
                $field instanceof FieldReference => $this->orderBy($field, Direction::descending),
                is_string($field) => $this->orderByMeta($field, Direction::descending),
            };
        }

        return $this;
    }

    public function orderByMeta(string $meta_key, Direction $direction = Direction::ascending): static
    {
        return $this->doOrderBy($meta_key, $direction);
    }

    private function doOrderBy(string $field_reference, Direction $direction): static
    {
        $this->arguments[self::ORDER_BY_KEY] = $field_reference;
        $this->arguments[self::DIRECTION_KEY] = $direction->value;

        return $this;
    }

    private function doNotOrderBy(): static
    {
        unset($this->arguments[self::DIRECTION_KEY]);
        $this->arguments[self::ORDER_BY_KEY] = 'none';

        return $this;
    }
}
