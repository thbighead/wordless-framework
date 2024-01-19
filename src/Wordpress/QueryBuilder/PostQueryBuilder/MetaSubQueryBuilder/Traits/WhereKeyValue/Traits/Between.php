<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;
use function Sodium\compare;

trait Between
{
    public function whereCharKeyValueBetween(string $key, string $min_value, string $max_value): static
    {
        return $this->whereKeyValueBetween($key, $min_value, $max_value, Type::char);
    }

    public function whereDateKeyValueBetween(string $key, Carbon $min_value, Carbon $max_value): static
    {
        return $this->whereKeyValueBetween($key, $min_value->toDateString(), $max_value->toDateString(), Type::date);
    }

    public function whereDateTimeKeyValueBetween(string $key, Carbon $min_value, Carbon $max_value): static
    {
        return $this->whereKeyValueBetween(
            $key,
            $min_value->toDateTimeString(),
            $max_value->toDateTimeString(),
            Type::datetime
        );
    }

    public function whereDecimalKeyValueBetween(string $key, float $min_value, float $max_value): static
    {
        return $this->whereKeyValueBetween($key, $min_value, $max_value, Type::decimal);
    }

    public function whereNumericKeyValueBetween(string $key, int|float $min_value, int|float $max_value): static
    {
        return $this->whereKeyValueBetween($key, $min_value, $max_value, Type::numeric);
    }

    public function whereSignedKeyValueBetween(string $key, int $min_value, int $max_value): static
    {
        return $this->whereKeyValueBetween($key, $min_value, $max_value, Type::signed);
    }

    public function whereTimeKeyValueBetween(string $key, Carbon $min_value, Carbon $max_value): static
    {
        return $this->whereKeyValueBetween($key, $min_value->toTimeString(), $max_value->toTimeString(), Type::time);
    }

    public function whereUnsignedKeyValueBetween(string $key, int $min_value, int $max_value): static
    {
        return $this->whereKeyValueBetween($key, $min_value, $max_value, Type::unsigned);
    }

    private function whereKeyValueBetween(
        string $key,
        string|int|float $min_value,
        string|int|float $max_value,
        Type             $valueType
    ): static
    {
        $this->arguments[] = $this->mountArgument(
            [$min_value, $max_value],
            $valueType,
            $key,
            Compare::between
        );

        return $this;
    }
}
