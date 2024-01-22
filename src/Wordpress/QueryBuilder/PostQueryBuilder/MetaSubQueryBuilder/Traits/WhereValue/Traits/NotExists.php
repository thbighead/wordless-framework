<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait NotExists
{
    private const NOT_EXISTS_BUG_VALUE = 'bug #23268';

    public function whereBinaryValueNotExists(bool $value): static
    {
        return $this->whereValueNotExists($value, Type::binary);
    }

    public function whereCharValueNotExists(string $value): static
    {
        return $this->whereValueNotExists($value, Type::char);
    }

    public function whereDateValueNotExists(Carbon $value): static
    {
        return $this->whereValueNotExists($value->toDateString(), Type::date);
    }

    public function whereDateTimeValueNotExists(Carbon $value): static
    {
        return $this->whereValueNotExists($value->toDateTimeString(), Type::datetime);
    }

    public function whereDecimalValueNotExists(float $value): static
    {
        return $this->whereValueNotExists($value, Type::decimal);
    }

    public function whereNumericValueNotExists(int|float $value): static
    {
        return $this->whereValueNotExists($value, Type::numeric);
    }

    public function whereSignedValueNotExists(int $value): static
    {
        return $this->whereValueNotExists($value, Type::signed);
    }

    public function whereTimeValueNotExists(Carbon $value): static
    {
        return $this->whereValueNotExists($value->toTimeString(), Type::time);
    }

    public function whereUnsignedValueNotExists(int $value): static
    {
        return $this->whereValueNotExists($value, Type::unsigned);
    }

    private function whereValueNotExists(string|int|float|bool $value, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument(
            $value,
            $valueType,
            compare: Compare::not_exists
        );

        return $this;
    }
}
