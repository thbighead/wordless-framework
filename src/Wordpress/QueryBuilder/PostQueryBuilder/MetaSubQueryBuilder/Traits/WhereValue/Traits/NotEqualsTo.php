<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait NotEqualsTo
{
    public function whereBinaryValueNotEqualsTo(bool $value): static
    {
        return $this->whereValueNotEqualsTo($value, Type::binary);
    }

    public function whereCharValueNotEqualsTo(string $value): static
    {
        return $this->whereValueNotEqualsTo($value, Type::char);
    }

    public function whereDateValueNotEqualsTo(Carbon $value): static
    {
        return $this->whereValueNotEqualsTo($value->toDateString(), Type::date);
    }

    public function whereDateTimeValueNotEqualsTo(Carbon $value): static
    {
        return $this->whereValueNotEqualsTo($value->toDateTime(), Type::datetime);
    }

    public function whereDecimalValueNotEqualsTo(float $value): static
    {
        return $this->whereValueNotEqualsTo($value, Type::decimal);
    }

    public function whereNumericValueNotEqualsTo(int|float $value): static
    {
        return $this->whereValueNotEqualsTo($value, Type::numeric);
    }

    public function whereSignedValueNotEqualsTo(int $value): static
    {
        return $this->whereValueNotEqualsTo($value, Type::signed);
    }

    public function whereTimeValueNotEqualsTo(Carbon $value): static
    {
        return $this->whereValueNotEqualsTo($value->toTimeString(), Type::time);
    }

    public function whereUnsignedValueNotEqualsTo(int $value): static
    {
        return $this->whereValueNotEqualsTo($value, Type::unsigned);
    }

    /**
     * @param string|int|float|bool|array<int, string|int|float|bool> $value
     * @param Type $valueType
     * @return $this
     */
    private function whereValueNotEqualsTo(string|int|float|bool|array $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, compare: Compare::not_equals);

        return $this;
    }
}
