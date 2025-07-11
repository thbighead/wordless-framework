<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait NotIn
{
    public function whereKeyCharValueNotIn(string $key, string $value, string ...$values): static
    {
        return $this->whereKeyValueNotIn($key, Arr::prepend($values, $value), Type::char);
    }

    public function whereKeyDateValueNotIn(string $key, Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toDateString();
        }

        return $this->whereKeyValueNotIn($key, $prepared_values, Type::date);
    }

    public function whereKeyDateTimeValueNotIn(string $key, Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toDateTimeString();
        }

        return $this->whereKeyValueNotIn($key, $prepared_values, Type::datetime);
    }

    public function whereKeyDecimalValueNotIn(string $key, float $value, float ...$values): static
    {
        return $this->whereKeyValueNotIn($key, Arr::prepend($values, $value), Type::decimal);
    }

    public function whereKeyNumericValueNotIn(string $key, int|float $value, int|float ...$values): static
    {
        return $this->whereKeyValueNotIn($key, Arr::prepend($values, $value), Type::numeric);
    }

    public function whereKeySignedValueNotIn(string $key, int $value, int ...$values): static
    {
        return $this->whereKeyValueNotIn($key, Arr::prepend($values, $value), Type::signed);
    }

    public function whereKeyTimeValueNotIn(string $key, Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toTimeString();
        }

        return $this->whereKeyValueNotIn($key, $prepared_values, Type::time);
    }

    public function whereKeyUnsignedValueNotIn(string $key, int $value, int ...$values): static
    {
        return $this->whereKeyValueNotIn($key, Arr::prepend($values, $value), Type::unsigned);
    }

    private function whereKeyValueNotIn(string $key, array $values, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($values, $valueType, $key, Compare::not_in);

        return $this;
    }
}
