<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait LessThan
{
    public function whereCharKeyValueLessThan(string $key, string $value): static
    {
        return $this->whereKeyValueLessThan($key, $value, Type::char);
    }

    public function whereDateKeyValueLessThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThan($key, $value->toDateString(), Type::date);
    }

    public function whereDateTimeKeyValueLessThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThan($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalKeyValueLessThan(string $key, float $value): static
    {
        return $this->whereKeyValueLessThan($key, $value, Type::decimal);
    }

    public function whereNumericKeyValueLessThan(string $key, int|float $value): static
    {
        return $this->whereKeyValueLessThan($key, $value, Type::numeric);
    }

    public function whereSignedKeyValueLessThan(string $key, int $value): static
    {
        return $this->whereKeyValueLessThan($key, $value, Type::signed);
    }

    public function whereTimeKeyValueLessThan(string $key, Carbon $value): static
    {
        return $this->whereKeyValueLessThan($key, $value->toTimeString(), Type::time);
    }

    public function whereUnsignedKeyValueLessThan(string $key, int $value): static
    {
        return $this->whereKeyValueLessThan($key, $value, Type::unsigned);
    }

    private function whereKeyValueLessThan(string $key, string|int|float $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key, Compare::less_than);

        return $this;
    }
}
