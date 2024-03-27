<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Carbon\Carbon;
use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait In
{
    public function whereCharValueIn(string $value, string ...$values): static
    {
        return $this->whereValueIn(Arr::prepend($values, $value), Type::char);
    }

    public function whereDateValueIn(Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toDateString();
        }

        return $this->whereValueIn($prepared_values, Type::date);
    }

    public function whereDateTimeValueIn(Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toDateTimeString();
        }

        return $this->whereValueIn($prepared_values, Type::datetime);
    }

    public function whereDecimalValueIn(float $value, float ...$values): static
    {
        return $this->whereValueIn(Arr::prepend($values, $value), Type::decimal);
    }

    public function whereNumericValueIn(int|float $value, int|float ...$values): static
    {
        return $this->whereValueIn(Arr::prepend($values, $value), Type::numeric);
    }

    public function whereSignedValueIn(int $value, int ...$values): static
    {
        return $this->whereValueIn(Arr::prepend($values, $value), Type::signed);
    }

    public function whereTimeValueIn(Carbon $value, Carbon ...$values): static
    {
        $prepared_values = [];

        foreach (Arr::prepend($values, $value) as $unpreparedValues) {
            /** @var Carbon $unpreparedValues */
            $prepared_values[] = $unpreparedValues->toTimeString();
        }

        return $this->whereValueIn($prepared_values, Type::time);
    }

    public function whereUnsignedValueIn(int $value, int ...$values): static
    {
        return $this->whereValueIn(Arr::prepend($values, $value), Type::unsigned);
    }

    private function whereValueIn(array $values, Type $valueType): static
    {
        $this->arguments[] = $this->mountArgument($values, $valueType, compare: Compare::in);

        return $this;
    }
}
