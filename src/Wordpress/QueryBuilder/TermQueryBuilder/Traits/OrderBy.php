<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\Enums\Direction;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Type;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\OrderBy\Enums\FieldReference;

trait OrderBy
{
    private const DIRECTION_KEY = 'order';
    private const META_ORDER_BY_TYPE_KEY = 'meta_order_by_type';
    private const ORDER_BY_KEY = 'orderby';

    public function orderBy(FieldReference $field, Direction $direction = Direction::ascending): static
    {
        return $this->doOrderBy($field->value, $direction);
    }

    public function orderByMeta(string $meta_key, Type $metaType, Direction $direction = Direction::ascending): static
    {
        $this->arguments[self::META_ORDER_BY_TYPE_KEY] = $metaType;

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
