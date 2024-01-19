<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait NotEqualsTo
{
    public function whereBinaryValueNotEqualsTo(string $key, bool $value): static
    {
        return $this->whereValueNotEqualsTo($key, $value, Type::binary);
    }

    public function whereCharValueNotEqualsTo(string $key, string $value): static
    {
        return $this->whereValueNotEqualsTo($key, $value, Type::char);
    }

    public function whereDateValueNotEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereValueNotEqualsTo($key, $value->toDateString(), Type::date);
    }

    public function whereDateTimeValueNotEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereValueNotEqualsTo($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalValueNotEqualsTo(string $key, float $value): static
    {
        return $this->whereValueNotEqualsTo($key, $value, Type::decimal);
    }

    public function whereNumericValueNotEqualsTo(string $key, int|float $value): static
    {
        return $this->whereValueNotEqualsTo($key, $value, Type::numeric);
    }

    public function whereSignedValueNotEqualsTo(string $key, int $value): static
    {
        return $this->whereValueNotEqualsTo($key, $value, Type::signed);
    }

    public function whereTimeValueNotEqualsTo(string $key, Carbon $value): static
    {
        return $this->whereValueNotEqualsTo($key, $value->toTimeString(), Type::time);
    }

    public function whereUnsignedValueNotEqualsTo(string $key, int $value): static
    {
        return $this->whereValueNotEqualsTo($key, $value, Type::unsigned);
    }

    private function whereValueNotEqualsTo(string $key, string|int|float|bool $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key, Compare::not_equals);

        return $this;
    }
}
