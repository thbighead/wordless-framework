<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Type;

trait LessThan
{
    public function whereKeyCharValueLessThan(string $key, string $value): static
    {
        return $this->whereKeyValueLessThan($key, $value, Type::char);
    }

    public function whereKeyDateValueLessThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThan($key, $value->toDateString(), Type::date);
    }

    public function whereKeyDateTimeValueLessThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThan($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereKeyDecimalValueLessThan(string $key, float $value): static
    {
        return $this->whereKeyValueLessThan($key, $value, Type::decimal);
    }

    public function whereKeyNumericValueLessThan(string $key, int|float $value): static
    {
        return $this->whereKeyValueLessThan($key, $value, Type::numeric);
    }

    public function whereKeySignedValueLessThan(string $key, int $value): static
    {
        return $this->whereKeyValueLessThan($key, $value, Type::signed);
    }

    public function whereKeyTimeValueLessThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThan($key, $value->toTimeString(), Type::time);
    }

    public function whereKeyUnsignedValueLessThan(string $key, int $value): static
    {
        return $this->whereKeyValueLessThan($key, $value, Type::unsigned);
    }

    private function whereKeyValueLessThan(string $key, string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key, Compare::less_than);

        return $this;
    }
}
