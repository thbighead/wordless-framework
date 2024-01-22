<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait NotExists
{
    private const NOT_EXISTS_BUG_VALUE = 'bug #23268';

    public function whereKeyBinaryValueNotExists(string $key, bool $value): static
    {
        return $this->whereKeyValueNotExists($key, $value, Type::binary);
    }

    public function whereKeyCharValueNotExists(string $key, string $value): static
    {
        return $this->whereKeyValueNotExists($key, $value);
    }

    public function whereKeyDateValueNotExists(string $key, Carbon $value): static
    {
        return $this->whereKeyValueNotExists($key, $value->toDateString(), Type::date);
    }

    public function whereKeyDateTimeValueNotExists(string $key, Carbon $value): static
    {
        return $this->whereKeyValueNotExists($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereKeyDecimalValueNotExists(string $key, float $value): static
    {
        return $this->whereKeyValueNotExists($key, $value, Type::decimal);
    }

    public function whereKeyNotExists(string $key): static
    {
        return $this->whereKeyValueNotExists($key);
    }

    public function whereKeyNumericValueNotExists(string $key, int|float $value): static
    {
        return $this->whereKeyValueNotExists($key, $value, Type::numeric);
    }

    public function whereKeySignedValueNotExists(string $key, int $value): static
    {
        return $this->whereKeyValueNotExists($key, $value, Type::signed);
    }

    public function whereKeyTimeValueNotExists(string $key, Carbon $value): static
    {
        return $this->whereKeyValueNotExists($key, $value->toTimeString(), Type::time);
    }

    public function whereKeyUnsignedValueNotExists(string $key, int $value): static
    {
        return $this->whereKeyValueNotExists($key, $value, Type::unsigned);
    }

    private function whereKeyValueNotExists(
        string                     $key,
        string|int|float|bool|null $value = null,
        Type                       $valueType = Type::char
    ): static
    {
        $this->arguments[] = $this->mountArgument(
            $value ?? self::NOT_EXISTS_BUG_VALUE,
            $valueType,
            $key,
            Compare::not_exists
        );

        return $this;
    }
}
