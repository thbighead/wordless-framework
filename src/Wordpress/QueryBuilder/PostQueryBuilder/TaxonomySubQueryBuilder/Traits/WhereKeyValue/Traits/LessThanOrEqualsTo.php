<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Type;

trait LessThanOrEqualsTo
{
    public function whereKeyCharValueLessThanOrEqualsTo(string $key, string $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value, Type::char);
    }

    public function whereKeyDateValueLessThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value->toDateString(), Type::date);
    }

    public function whereKeyDateTimeValueLessThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereKeyDecimalValueLessThanOrEqualsTo(string $key, float $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value, Type::decimal);
    }

    public function whereKeyNumericValueLessThanOrEqualsTo(string $key, int|float $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value, Type::numeric);
    }

    public function whereKeySignedValueLessThanOrEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value, Type::signed);
    }

    public function whereKeyTimeValueLessThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value->toTimeString(), Type::time);
    }

    public function whereKeyUnsignedValueLessThanOrEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value, Type::unsigned);
    }

    private function whereKeyValueLessThanOrEqualsTo(string $key, string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key, Compare::less_than_or_equals);

        return $this;
    }
}
