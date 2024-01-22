<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Type;

trait NotEqualsTo
{
    public function whereKeyBinaryValueNotEqualsTo(string $key, bool $value): static
    {
        return $this->whereKeyValueNotEqualsTo($key, $value, Type::binary);
    }

    public function whereKeyCharValueNotEqualsTo(string $key, string $value): static
    {
        return $this->whereKeyValueNotEqualsTo($key, $value, Type::char);
    }

    public function whereKeyDateValueNotEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueNotEqualsTo($key, $value->toDateString(), Type::date);
    }

    public function whereKeyDateTimeValueNotEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueNotEqualsTo($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereKeyDecimalValueNotEqualsTo(string $key, float $value): static
    {
        return $this->whereKeyValueNotEqualsTo($key, $value, Type::decimal);
    }

    public function whereKeyNumericValueNotEqualsTo(string $key, int|float $value): static
    {
        return $this->whereKeyValueNotEqualsTo($key, $value, Type::numeric);
    }

    public function whereKeySignedValueNotEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueNotEqualsTo($key, $value, Type::signed);
    }

    public function whereKeyTimeValueNotEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueNotEqualsTo($key, $value->toTimeString(), Type::time);
    }

    public function whereKeyUnsignedValueNotEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueNotEqualsTo($key, $value, Type::unsigned);
    }

    private function whereKeyValueNotEqualsTo(string $key, string|int|float|bool $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key, Compare::not_equals);

        return $this;
    }
}
