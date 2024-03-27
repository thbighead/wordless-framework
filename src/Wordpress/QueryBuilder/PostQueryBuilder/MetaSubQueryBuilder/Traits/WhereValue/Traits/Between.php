<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait Between
{
    public function whereCharValueBetween(string $min_value, string $max_value): static
    {
        return $this->whereValueBetween($min_value, $max_value, Type::char);
    }

    public function whereDateValueBetween(Carbon $min_value, Carbon $max_value): static
    {
        return $this->whereValueBetween($min_value->toDateString(), $max_value->toDateString(), Type::date);
    }

    public function whereDateTimeValueBetween(Carbon $min_value, Carbon $max_value): static
    {
        return $this->whereValueBetween(
            $min_value->toDateTimeString(),
            $max_value->toDateTimeString(),
            Type::datetime
        );
    }

    public function whereDecimalValueBetween(float $min_value, float $max_value): static
    {
        return $this->whereValueBetween($min_value, $max_value, Type::decimal);
    }

    public function whereNumericValueBetween(int|float $min_value, int|float $max_value): static
    {
        return $this->whereValueBetween($min_value, $max_value, Type::numeric);
    }

    public function whereSignedValueBetween(int $min_value, int $max_value): static
    {
        return $this->whereValueBetween($min_value, $max_value, Type::signed);
    }

    public function whereTimeValueBetween(Carbon $min_value, Carbon $max_value): static
    {
        return $this->whereValueBetween($min_value->toTimeString(), $max_value->toTimeString(), Type::time);
    }

    public function whereUnsignedValueBetween(int $min_value, int $max_value): static
    {
        return $this->whereValueBetween($min_value, $max_value, Type::unsigned);
    }

    private function whereValueBetween(
        string|int|float $min_value,
        string|int|float $max_value,
        Type             $valueType
    ): static
    {
        $this->arguments[] = $this->mountArgument([$min_value, $max_value], $valueType, compare: Compare::between);

        return $this;
    }
}
