<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Carbon\Carbon;
use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait NotIn
{
    public function whereKeyCharValueNotIn(string $value, string ...$values): static
    {
        return $this->whereKeyValueNotIn(Arr::prepend($values, $value), Type::char);
    }

    public function whereKeyDateValueNotIn(Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toDateString();
        }

        return $this->whereKeyValueNotIn($prepared_values, Type::date);
    }

    public function whereKeyDateTimeValueNotIn(Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toDateTimeString();
        }

        return $this->whereKeyValueNotIn($prepared_values, Type::datetime);
    }

    public function whereKeyDecimalValueNotIn(float $value, float ...$values): static
    {
        return $this->whereKeyValueNotIn(Arr::prepend($values, $value), Type::decimal);
    }

    public function whereKeyNumericValueNotIn(int|float $value, int|float ...$values): static
    {
        return $this->whereKeyValueNotIn(Arr::prepend($values, $value), Type::numeric);
    }

    public function whereKeySignedValueNotIn(int $value, int ...$values): static
    {
        return $this->whereKeyValueNotIn(Arr::prepend($values, $value), Type::signed);
    }

    public function whereKeyTimeValueNotIn(Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toTimeString();
        }

        return $this->whereKeyValueNotIn($prepared_values, Type::time);
    }

    public function whereKeyUnsignedValueNotIn(int $value, int ...$values): static
    {
        return $this->whereKeyValueNotIn(Arr::prepend($values, $value), Type::unsigned);
    }

    private function whereKeyValueNotIn(array $values, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($values, $valueType, compare: Compare::not_in);

        return $this;
    }
}
