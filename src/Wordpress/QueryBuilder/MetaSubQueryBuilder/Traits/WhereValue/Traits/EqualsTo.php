<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait EqualsTo
{
    public function whereBinaryValueEqualsTo(bool $value): static
    {
        return $this->whereValueEqualsTo($value, Type::binary);
    }

    public function whereCharValueEqualsTo(string $value): static
    {
        return $this->whereValueEqualsTo($value, Type::char);
    }

    public function whereDateValueEqualsTo(Carbon $value): static
    {
        return $this->whereValueEqualsTo($value->toDateString(), Type::date);
    }

    public function whereDateTimeValueEqualsTo(Carbon $value): static
    {
        return $this->whereValueEqualsTo($value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalValueEqualsTo(float $value): static
    {
        return $this->whereValueEqualsTo($value, Type::decimal);
    }

    public function whereNumericValueEqualsTo(int|float $value): static
    {
        return $this->whereValueEqualsTo($value, Type::numeric);
    }

    public function whereSignedValueEqualsTo(int $value): static
    {
        return $this->whereValueEqualsTo($value, Type::signed);
    }

    public function whereTimeValueEqualsTo(Carbon $value): static
    {
        return $this->whereValueEqualsTo($value->toTimeString(), Type::time);
    }

    public function whereUnsignedValueEqualsTo(int $value): static
    {
        return $this->whereValueEqualsTo($value, Type::unsigned);
    }

    private function whereValueEqualsTo(string|int|float|bool $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType);

        return $this;
    }
}
