<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait GreaterThanOrEqualsTo
{
    public function whereCharKeyValueGreaterThanOrEqualsTo(string $key, string $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value, Type::char);
    }

    public function whereDateKeyValueGreaterThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value->toDateString(), Type::date);
    }

    public function whereDateTimeKeyValueGreaterThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalKeyValueGreaterThanOrEqualsTo(string $key, float $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value, Type::decimal);
    }

    public function whereNumericKeyValueGreaterThanOrEqualsTo(string $key, int|float $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value, Type::numeric);
    }

    public function whereSignedKeyValueGreaterThanOrEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value, Type::signed);
    }

    public function whereTimeKeyValueGreaterThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value->toTimeString(), Type::time);
    }

    public function whereUnsignedKeyValueGreaterThanOrEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueGreaterThanOrEqualsTo($key, $value, Type::unsigned);
    }

    private function whereKeyValueGreaterThanOrEqualsTo(string $key, string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key, Compare::greater_than_or_equals);

        return $this;
    }
}
