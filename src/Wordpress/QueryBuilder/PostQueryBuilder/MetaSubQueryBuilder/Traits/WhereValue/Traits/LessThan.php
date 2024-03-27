<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait LessThan
{
    public function whereCharValueLessThan(string $value): static
    {
        return $this->whereValueLessThan($value, Type::char);
    }

    public function whereDateValueLessThan(Carbon $value): static
    {
        return $this->whereValueLessThan($value->toDateString(), Type::date);
    }

    public function whereDateTimeValueLessThan(Carbon $value): static
    {
        return $this->whereValueLessThan($value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalValueLessThan(float $value): static
    {
        return $this->whereValueLessThan($value, Type::decimal);
    }

    public function whereNumericValueLessThan(int|float $value): static
    {
        return $this->whereValueLessThan($value, Type::numeric);
    }

    public function whereSignedValueLessThan(int $value): static
    {
        return $this->whereValueLessThan($value, Type::signed);
    }

    public function whereTimeValueLessThan(Carbon $value): static
    {
        return $this->whereValueLessThan($value->toTimeString(), Type::time);
    }

    public function whereUnsignedValueLessThan(int $value): static
    {
        return $this->whereValueLessThan($value, Type::unsigned);
    }

    private function whereValueLessThan(string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, compare: Compare::less_than);

        return $this;
    }
}
