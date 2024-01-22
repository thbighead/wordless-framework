<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Type;

trait GreaterThan
{
    public function whereCharValueGreaterThan(string $value): static
    {
        return $this->whereValueGreaterThan($value, Type::char);
    }

    public function whereDateValueGreaterThan(Carbon $value): static
    {
        return $this->whereValueGreaterThan($value->toDateString(), Type::date);
    }

    public function whereDateTimeValueGreaterThan(Carbon $value): static
    {
        return $this->whereValueGreaterThan($value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalValueGreaterThan(float $value): static
    {
        return $this->whereValueGreaterThan($value, Type::decimal);
    }

    public function whereNumericValueGreaterThan(int|float $value): static
    {
        return $this->whereValueGreaterThan($value, Type::numeric);
    }

    public function whereSignedValueGreaterThan(int $value): static
    {
        return $this->whereValueGreaterThan($value, Type::signed);
    }

    public function whereTimeValueGreaterThan(Carbon $value): static
    {
        return $this->whereValueGreaterThan($value->toTimeString(), Type::time);
    }

    public function whereUnsignedValueGreaterThan(int $value): static
    {
        return $this->whereValueGreaterThan($value, Type::unsigned);
    }

    private function whereValueGreaterThan(string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, compare: Compare::greater_than);

        return $this;
    }
}
