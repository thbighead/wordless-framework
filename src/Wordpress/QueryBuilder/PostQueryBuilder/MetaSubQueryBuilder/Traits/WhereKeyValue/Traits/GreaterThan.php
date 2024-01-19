<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait GreaterThan
{
    public function whereCharKeyValueGreaterThan(string $key, string $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value, Type::char);
    }

    public function whereDateKeyValueGreaterThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value->toDateString(), Type::date);
    }

    public function whereDateTimeKeyValueGreaterThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalKeyValueGreaterThan(string $key, float $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value, Type::decimal);
    }

    public function whereNumericKeyValueGreaterThan(string $key, int|float $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value, Type::numeric);
    }

    public function whereSignedKeyValueGreaterThan(string $key, int $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value, Type::signed);
    }

    public function whereTimeKeyValueGreaterThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value->toTimeString(), Type::time);
    }

    public function whereUnsignedKeyValueGreaterThan(string $key, int $value): static
    {
        return $this->whereKeyValueGreaterThan($key, $value, Type::unsigned);
    }

    private function whereKeyValueGreaterThan(string $key, string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key, Compare::greater_than);

        return $this;
    }
}
