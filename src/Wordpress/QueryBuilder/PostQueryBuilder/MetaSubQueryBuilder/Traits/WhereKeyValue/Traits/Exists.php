<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait Exists
{
    public function whereKeyBinaryValueExists(string $key, bool $value): static
    {
        return $this->whereKeyValueExists($key, $value, Type::binary);
    }

    public function whereKeyCharValueExists(string $key, string $value): static
    {
        return $this->whereKeyValueExists($key, $value);
    }

    public function whereKeyDateValueExists(string $key, Carbon $value): static
    {
        return $this->whereKeyValueExists($key, $value->toDateString(), Type::date);
    }

    public function whereKeyDateTimeValueExists(string $key, Carbon $value): static
    {
        return $this->whereKeyValueExists($key, $value->toDateTimeString(), Type::datetime);
    }

    public function whereKeyDecimalValueExists(string $key, float $value): static
    {
        return $this->whereKeyValueExists($key, $value, Type::decimal);
    }

    public function whereKeyExists(string $key): static
    {
        return $this->whereKeyValueExists($key);
    }

    public function whereKeyNumericValueExists(string $key, int|float $value): static
    {
        return $this->whereKeyValueExists($key, $value, Type::numeric);
    }

    public function whereKeySignedValueExists(string $key, int $value): static
    {
        return $this->whereKeyValueExists($key, $value, Type::signed);
    }

    public function whereKeyTimeValueExists(string $key, Carbon $value): static
    {
        return $this->whereKeyValueExists($key, $value->toTimeString(), Type::time);
    }

    public function whereKeyUnsignedValueExists(string $key, int $value): static
    {
        return $this->whereKeyValueExists($key, $value, Type::unsigned);
    }

    private function whereKeyValueExists(
        string                     $key,
        string|int|float|bool|null $value = null,
        Type                       $valueType = Type::char
    ): static
    {
        $this->arguments[] = $this->mountArgument($value, $valueType, $key, Compare::exists);

        return $this;
    }
}
