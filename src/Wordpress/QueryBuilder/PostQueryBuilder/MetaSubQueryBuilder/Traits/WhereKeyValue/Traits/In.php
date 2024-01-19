<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait In
{
    public function whereCharKeyValueIn(string $key, string $value, string ...$values): static
    {
        return $this->whereKeyValueIn($key, Arr::prepend($values, $value), Type::char);
    }

    public function whereDateKeyValueIn(string $key, Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedKeyValues) {
            /** @var Carbon $unpreparedKeyValues */
            $prepared_values[] = $unpreparedKeyValues->toDateString();
        }

        return $this->whereKeyValueIn($key, $prepared_values, Type::date);
    }

    public function whereDateTimeKeyValueIn(string $key, Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedKeyValues) {
            /** @var Carbon $unpreparedKeyValues */
            $prepared_values[] = $unpreparedKeyValues->toDateTimeString();
        }

        return $this->whereKeyValueIn($key, $prepared_values, Type::datetime);
    }

    public function whereDecimalKeyValueIn(string $key, float $value, float ...$values): static
    {
        return $this->whereKeyValueIn($key, Arr::prepend($values, $value), Type::decimal);
    }

    public function whereNumericKeyValueIn(string $key, int|float $value, int|float ...$values): static
    {
        return $this->whereKeyValueIn($key, Arr::prepend($values, $value), Type::numeric);
    }

    public function whereSignedKeyValueIn(string $key, int $value, int ...$values): static
    {
        return $this->whereKeyValueIn($key, Arr::prepend($values, $value), Type::signed);
    }

    public function whereTimeKeyValueIn(string $key, Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedKeyValues) {
            /** @var Carbon $unpreparedKeyValues */
            $prepared_values[] = $unpreparedKeyValues->toTimeString();
        }

        return $this->whereKeyValueIn($key, $prepared_values, Type::time);
    }

    public function whereUnsignedKeyValueIn(string $key, int $value, int ...$values): static
    {
        return $this->whereKeyValueIn($key, Arr::prepend($values, $value), Type::unsigned);
    }

    private function whereKeyValueIn(string $key, array $values, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($values, $valueType, $key, Compare::in);

        return $this;
    }
}
