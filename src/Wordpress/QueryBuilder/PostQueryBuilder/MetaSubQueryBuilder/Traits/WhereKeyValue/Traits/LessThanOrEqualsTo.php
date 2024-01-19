<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait LessThanOrEqualsTo
{
    public function whereCharKeyValueLessThanOrEqualsTo(string $key, string $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value, Type::char);
    }

    public function whereDateKeyValueLessThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value->toDateString(), Type::date);
    }

    public function whereDateTimeKeyValueLessThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalKeyValueLessThanOrEqualsTo(string $key, float $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value, Type::decimal);
    }

    public function whereNumericKeyValueLessThanOrEqualsTo(string $key, int|float $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value, Type::numeric);
    }

    public function whereSignedKeyValueLessThanOrEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value, Type::signed);
    }

    public function whereTimeKeyValueLessThanOrEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value->toTimeString(), Type::time);
    }

    public function whereUnsignedKeyValueLessThanOrEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueLessThanOrEqualsTo($key, $value, Type::unsigned);
    }

    private function whereKeyValueLessThanOrEqualsTo(string $key, string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key, Compare::less_than_or_equals);

        return $this;
    }
}
