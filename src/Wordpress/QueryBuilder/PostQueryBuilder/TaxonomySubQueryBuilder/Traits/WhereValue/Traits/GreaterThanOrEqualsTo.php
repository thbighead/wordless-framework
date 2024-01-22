<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Type;

trait GreaterThanOrEqualsTo
{
    public function whereCharValueGreaterThanOrEqualsTo(string $value): static
    {
        return $this->whereValueGreaterThanOrEqualsTo($value, Type::char);
    }

    public function whereDateValueGreaterThanOrEqualsTo(Carbon $value): static
    {
        return $this->whereValueGreaterThanOrEqualsTo($value->toDateString(), Type::date);
    }

    public function whereDateTimeValueGreaterThanOrEqualsTo(Carbon $value): static
    {
        return $this->whereValueGreaterThanOrEqualsTo($value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalValueGreaterThanOrEqualsTo(float $value): static
    {
        return $this->whereValueGreaterThanOrEqualsTo($value, Type::decimal);
    }

    public function whereNumericValueGreaterThanOrEqualsTo(int|float $value): static
    {
        return $this->whereValueGreaterThanOrEqualsTo($value, Type::numeric);
    }

    public function whereSignedValueGreaterThanOrEqualsTo(int $value): static
    {
        return $this->whereValueGreaterThanOrEqualsTo($value, Type::signed);
    }

    public function whereTimeValueGreaterThanOrEqualsTo(Carbon $value): static
    {
        return $this->whereValueGreaterThanOrEqualsTo($value->toTimeString(), Type::time);
    }

    public function whereUnsignedValueGreaterThanOrEqualsTo(int $value): static
    {
        return $this->whereValueGreaterThanOrEqualsTo($value, Type::unsigned);
    }

    private function whereValueGreaterThanOrEqualsTo(string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, compare: Compare::greater_than_or_equals);

        return $this;
    }
}
