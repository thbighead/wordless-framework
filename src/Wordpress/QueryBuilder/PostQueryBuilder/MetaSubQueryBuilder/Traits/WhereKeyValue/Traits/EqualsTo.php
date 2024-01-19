<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait EqualsTo
{
    public function whereBinaryKeyValueEqualsTo(string $key, bool $value): static
    {
        return $this->whereKeyValueEqualsTo($key, $value, Type::binary);
    }

    public function whereCharKeyValueEqualsTo(string $key, string $value): static
    {
        return $this->whereKeyValueEqualsTo($key, $value, Type::char);
    }

    public function whereDateKeyValueEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueEqualsTo($key, $value->toDateString(), Type::date);
    }

    public function whereDateTimeKeyValueEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueEqualsTo($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalKeyValueEqualsTo(string $key, float $value): static
    {
        return $this->whereKeyValueEqualsTo($key, $value, Type::decimal);
    }

    public function whereNumericKeyValueEqualsTo(string $key, int|float $value): static
    {
        return $this->whereKeyValueEqualsTo($key, $value, Type::numeric);
    }

    public function whereSignedKeyValueEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueEqualsTo($key, $value, Type::signed);
    }

    public function whereTimeKeyValueEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereKeyValueEqualsTo($key, $value->toTimeString(), Type::time);
    }

    public function whereUnsignedKeyValueEqualsTo(string $key, int $value): static
    {
        return $this->whereKeyValueEqualsTo($key, $value, Type::unsigned);
    }

    private function whereKeyValueEqualsTo(string $key, string|int|float|bool $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key);

        return $this;
    }
}
