<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait NotIn
{
    public function whereCharValueNotIn(string $value, string ...$values): static
    {
        return $this->whereValueNotIn(Arr::prepend($values, $value), Type::char);
    }

    public function whereDateValueNotIn(Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toDateString();
        }

        return $this->whereValueNotIn($prepared_values, Type::date);
    }

    public function whereDateTimeValueNotIn(Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toDateTimeString();
        }

        return $this->whereValueNotIn($prepared_values, Type::datetime);
    }

    public function whereDecimalValueNotIn(float $value, float ...$values): static
    {
        return $this->whereValueNotIn(Arr::prepend($values, $value), Type::decimal);
    }

    public function whereNumericValueNotIn(int|float $value, int|float ...$values): static
    {
        return $this->whereValueNotIn(Arr::prepend($values, $value), Type::numeric);
    }

    public function whereSignedValueNotIn(int $value, int ...$values): static
    {
        return $this->whereValueNotIn(Arr::prepend($values, $value), Type::signed);
    }

    public function whereTimeValueNotIn(Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toTimeString();
        }

        return $this->whereValueNotIn($prepared_values, Type::time);
    }

    public function whereUnsignedValueNotIn(int $value, int ...$values): static
    {
        return $this->whereValueNotIn(Arr::prepend($values, $value), Type::unsigned);
    }

    private function whereValueNotIn(array $values, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($values, $valueType, compare: Compare::not_in);

        return $this;
    }
}
