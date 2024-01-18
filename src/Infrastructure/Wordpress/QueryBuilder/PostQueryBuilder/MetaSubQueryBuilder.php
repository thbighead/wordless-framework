<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Enums\WpQueryMeta;
use Wordless\Exceptions\TryingToMakeNonArrayComparisonWithArrayableValues;
use Wordless\Exceptions\TryingToMakeOnlyForArrayComparisonWithNonArrayableValues;
use Wordless\Exceptions\UnexpectedMetaSubQueryClosureReturn;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\InitializedMetaSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Comparison;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Key;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

abstract class MetaSubQueryBuilder
{
    protected array $meta_sub_query_arguments = [];

    public function build(): array
    {
        return $this->meta_sub_query_arguments;
    }

    protected function buildClosureResult(MetaSubQueryBuilder $result): array
    {
        $arguments = $result->build();

        if ($result instanceof InitializedMetaSubQueryBuilder) {
            while (isset($arguments[0]) && count($arguments) === 1) {
                $arguments = $arguments[0];
            }
        }

        return $arguments;
    }

    protected function resolveClosure(callable $closure): array
    {
        $result = $closure(new EmptyMetaSubQueryBuilder);

        if ($result instanceof MetaSubQueryBuilder) {
            $result = $result->buildClosureResult($result);
        }

        if (!is_array($result)) {
            throw new UnexpectedMetaSubQueryClosureReturn($result);
        }

        return $result;
    }

    protected function setConditionToSubQuery(array $condition)
    {
        $this->meta_sub_query_arguments[] = $condition;
    }

    /**
     * @param string $key
     * @param string $comparison
     * @param mixed $values
     * @param string $value_typed_as
     * @return array|string[]
     * @throws TryingToMakeNonArrayComparisonWithArrayableValues
     * @throws TryingToMakeOnlyForArrayComparisonWithNonArrayableValues
     */
    protected function mountCondition(
        string     $key,
        Comparison $comparison,
                   $values = [],
        Type       $value_typed_as = Type::type_char
    ): array
    {
        if ($this->isTryingToCompareKeyExistenceWithoutValues($comparison, $values)) {
            return [
                $this->mountKeyMetaKey() => $key,
                $this->mountKeyCompare() => $comparison,
            ];
        }

        $this->validateValueComparison($comparison, $values);

        return [
            $this->mountKeyMetaKey() => $key,
            $this->mountKeyMetaValue() => $this->prepareValues($values),
            $this->mountKeyCompare() => $comparison,
            $this->mountKeyValueType() => $value_typed_as,
        ];
    }

    protected function mountKeyCompare(): string
    {
        return Key::key_compare->value;
    }

    protected function mountKeyMetaKey(): string
    {
        return Key::key_meta_key->value;
    }

    protected function mountKeyMetaValue(): string
    {
        return Key::key_meta_value->value;
    }

    protected function mountKeyValueType(): string
    {
        return Key::key_value_type->value;
    }

    protected function prepareValues($values)
    {
        if ($values === '0' || $values === 0) {
            return Key::zero_value_key->value;
        }

        return $values;
    }

    private function isTryingToCompareKeyExistenceWithoutValues(Comparison $comparison, $values): bool
    {
        return $comparison->isAvailableForMetaKeyComparison() && is_array($values) && empty($values);
    }

    /**
     * @param string $comparison
     * @param $values
     * @return void
     * @throws TryingToMakeNonArrayComparisonWithArrayableValues
     * @throws TryingToMakeOnlyForArrayComparisonWithNonArrayableValues
     */
    private function validateValueComparison(Comparison $comparison, $values): void
    {
        if ($comparison->isOnlyForArraysComparison() && !WpQueryMeta::isArrayableValue($values)) {
            throw new TryingToMakeOnlyForArrayComparisonWithNonArrayableValues($comparison, $values);
        }

        if (!WpQueryMeta::isOnlyForArraysComparison($comparison) && WpQueryMeta::isArrayableValue($values)) {
            throw new TryingToMakeNonArrayComparisonWithArrayableValues($comparison, $values);
        }
    }
}
