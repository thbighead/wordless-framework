<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Type;

trait GreaterThanOrEqualsTo
{
    public function whereKeyCharValueGreaterThanOrEqualsTo(string $key, string $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value, Type::char);
    }

    public function whereKeyDateValueGreaterThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value->toDateString(), Type::date);
    }

    public function whereKeyDateTimeValueGreaterThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereKeyDecimalValueGreaterThanOrEqualsTo(string $key, float $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value, Type::decimal);
    }

    public function whereKeyNumericValueGreaterThanOrEqualsTo(string $key, int|float $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value, Type::numeric);
    }

    public function whereKeySignedValueGreaterThanOrEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value, Type::signed);
    }

    public function whereKeyTimeValueGreaterThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value->toTimeString(), Type::time);
    }

    public function whereKeyUnsignedValueGreaterThanOrEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value, Type::unsigned);
    }

    private function whereKeyValueGreaterThanOrEqualsTo(string $key, string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key, Compare::greater_than_or_equals);

        return $this;
    }
}
