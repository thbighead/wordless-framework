<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait LessThanOrEqualsTo
{
    public function whereCharValueLessThanOrEqualsTo(string $value): static
    {
        return $this->whereValueLessThanOrEqualsTo($value, Type::char);
    }

    public function whereDateValueLessThanOrEqualsTo(Carbon $value): static
    {
        return $this->whereValueLessThanOrEqualsTo($value->toDateString(), Type::date);
    }

    public function whereDateTimeValueLessThanOrEqualsTo(Carbon $value): static
    {
        return $this->whereValueLessThanOrEqualsTo($value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalValueLessThanOrEqualsTo(float $value): static
    {
        return $this->whereValueLessThanOrEqualsTo($value, Type::decimal);
    }

    public function whereNumericValueLessThanOrEqualsTo(int|float $value): static
    {
        return $this->whereValueLessThanOrEqualsTo($value, Type::numeric);
    }

    public function whereSignedValueLessThanOrEqualsTo(int $value): static
    {
        return $this->whereValueLessThanOrEqualsTo($value, Type::signed);
    }

    public function whereTimeValueLessThanOrEqualsTo(Carbon $value): static
    {
        return $this->whereValueLessThanOrEqualsTo($value->toTimeString(), Type::time);
    }

    public function whereUnsignedValueLessThanOrEqualsTo(int $value): static
    {
        return $this->whereValueLessThanOrEqualsTo($value, Type::unsigned);
    }

    private function whereValueLessThanOrEqualsTo(string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, compare: Compare::less_than_or_equals);

        return $this;
    }
}
