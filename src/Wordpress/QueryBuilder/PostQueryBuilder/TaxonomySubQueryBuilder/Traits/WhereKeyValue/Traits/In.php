<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Type;

trait In
{
    public function whereKeyCharValueIn(string $key, string $value, string ...$values): static
    {
        return $this->whereKeyValueIn($key, Arr::prepend($values, $value), Type::char);
    }

    public function whereKeyDateValueIn(string $key, Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toDateString();
        }

        return $this->whereKeyValueIn($key, $prepared_values, Type::date);
    }

    public function whereKeyDateTimeValueIn(string $key, Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toDateTimeString();
        }

        return $this->whereKeyValueIn($key, $prepared_values, Type::datetime);
    }

    public function whereKeyDecimalValueIn(string $key, float $value, float ...$values): static
    {
        return $this->whereKeyValueIn($key, Arr::prepend($values, $value), Type::decimal);
    }

    public function whereKeyNumericValueIn(string $key, int|float $value, int|float ...$values): static
    {
        return $this->whereKeyValueIn($key, Arr::prepend($values, $value), Type::numeric);
    }

    public function whereKeySignedValueIn(string $key, int $value, int ...$values): static
    {
        return $this->whereKeyValueIn($key, Arr::prepend($values, $value), Type::signed);
    }

    public function whereKeyTimeValueIn(string $key, Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toTimeString();
        }

        return $this->whereKeyValueIn($key, $prepared_values, Type::time);
    }

    public function whereKeyUnsignedValueIn(string $key, int $value, int ...$values): static
    {
        return $this->whereKeyValueIn($key, Arr::prepend($values, $value), Type::unsigned);
    }

    private function whereKeyValueIn(string $key, array $values, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($values, $valueType, $key, Compare::in);

        return $this;
    }
}
