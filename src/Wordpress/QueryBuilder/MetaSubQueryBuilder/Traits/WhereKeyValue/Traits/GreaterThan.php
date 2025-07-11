<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait GreaterThan
{
    public function whereKeyCharValueGreaterThan(string $key, string $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value, Type::char);
    }

    public function whereKeyDateValueGreaterThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value->toDateString(), Type::date);
    }

    public function whereKeyDateTimeValueGreaterThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereKeyDecimalValueGreaterThan(string $key, float $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value, Type::decimal);
    }

    public function whereKeyNumericValueGreaterThan(string $key, int|float $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value, Type::numeric);
    }

    public function whereKeySignedValueGreaterThan(string $key, int $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value, Type::signed);
    }

    public function whereKeyTimeValueGreaterThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value->toTimeString(), Type::time);
    }

    public function whereKeyUnsignedValueGreaterThan(string $key, int $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value, Type::unsigned);
    }

    private function whereKeyValueGreaterThan(string $key, string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key, Compare::greater_than);

        return $this;
    }
}
