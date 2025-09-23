<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait NotBetween
{
    public function whereCharValueNotBetween(string $min_value, string $max_value): static
    {
        return $this->whereValueNotBetween($min_value, $max_value, Type::char);
    }

    public function whereDateValueNotBetween(Carbon $min_value, Carbon $max_value): static
    {
        return $this->whereValueNotBetween($min_value->toDateString(), $max_value->toDateString(), Type::date);
    }

    public function whereDateTimeValueNotBetween(Carbon $min_value, Carbon $max_value): static
    {
        return $this->whereValueNotBetween(
            $min_value->toDateTimeString(),
            $max_value->toDateTimeString(),
            Type::datetime
        );
    }

    public function whereDecimalValueNotBetween(float $min_value, float $max_value): static
    {
        return $this->whereValueNotBetween($min_value, $max_value, Type::decimal);
    }

    public function whereNumericValueNotBetween(int|float $min_value, int|float $max_value): static
    {
        return $this->whereValueNotBetween($min_value, $max_value, Type::numeric);
    }

    public function whereSignedValueNotBetween(int $min_value, int $max_value): static
    {
        return $this->whereValueNotBetween($min_value, $max_value, Type::signed);
    }

    public function whereTimeValueNotBetween(Carbon $min_value, Carbon $max_value): static
    {
        return $this->whereValueNotBetween($min_value->toTimeString(), $max_value->toTimeString(), Type::time);
    }

    public function whereUnsignedValueNotBetween(int $min_value, int $max_value): static
    {
        return $this->whereValueNotBetween($min_value, $max_value, Type::unsigned);
    }

    private function whereValueNotBetween(
        string|int|float $min_value,
        string|int|float $max_value,
        Type             $valueType
    ): static
    {
        $this->arguments[] = $this->mountArgument([$min_value, $max_value], $valueType, compare: Compare::not_between);

        return $this;
    }
}
