<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait Exists
{
    public function whereBinaryValueExists(bool $value): static
    {
        return $this->whereValueExists($value, Type::binary);
    }

    public function whereCharValueExists(string $value): static
    {
        return $this->whereValueExists($value, Type::char);
    }

    public function whereDateValueExists(Carbon $value): static
    {
        return $this->whereValueExists($value->toDateString(), Type::date);
    }

    public function whereDateTimeValueExists(Carbon $value): static
    {
        return $this->whereValueExists($value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalValueExists(float $value): static
    {
        return $this->whereValueExists($value, Type::decimal);
    }

    public function whereNumericValueExists(int|float $value): static
    {
        return $this->whereValueExists($value, Type::numeric);
    }

    public function whereSignedValueExists(int $value): static
    {
        return $this->whereValueExists($value, Type::signed);
    }

    public function whereTimeValueExists(Carbon $value): static
    {
        return $this->whereValueExists($value->toTimeString(), Type::time);
    }

    public function whereUnsignedValueExists(int $value): static
    {
        return $this->whereValueExists($value, Type::unsigned);
    }

    private function whereValueExists(string|int|float|bool $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, compare: Compare::exists);

        return $this;
    }
}
