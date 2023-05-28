<?php

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Enums\WpQueryMeta;
use Wordless\Exceptions\TryingToMakeNonArrayComparisonWithArrayableValues;
use Wordless\Exceptions\TryingToMakeOnlyForArrayComparisonWithNonArrayableValues;
use Wordless\Exceptions\UnexpectedMetaSubQueryClosureReturn;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\InitializedMetaSubQueryBuilder;

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
        string $key,
        string $comparison,
               $values = [],
        string $value_typed_as = WpQueryMeta::TYPE_CHAR
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
        return WpQueryMeta::KEY_COMPARE;
    }

    protected function mountKeyMetaKey(): string
    {
        return WpQueryMeta::KEY_META_KEY;
    }

    protected function mountKeyMetaValue(): string
    {
        return WpQueryMeta::KEY_META_VALUE;
    }

    protected function mountKeyValueType(): string
    {
        return WpQueryMeta::KEY_VALUE_TYPE;
    }

    protected function prepareValues($values)
    {
        if ($values === '0' || $values === 0) {
            return WpQueryMeta::ZERO_VALUE_KEY;
        }

        return $values;
    }

    private function isTryingToCompareKeyExistenceWithoutValues(string $comparison, $values): bool
    {
        return WpQueryMeta::isAvailableForMetaKeyComparison($comparison) && is_array($values) && empty($values);
    }

    /**
     * @param string $comparison
     * @param $values
     * @return void
     * @throws TryingToMakeNonArrayComparisonWithArrayableValues
     * @throws TryingToMakeOnlyForArrayComparisonWithNonArrayableValues
     */
    private function validateValueComparison(string $comparison, $values): void
    {
        if (WpQueryMeta::isOnlyForArraysComparison($comparison) && !WpQueryMeta::isArrayableValue($values)) {
            throw new TryingToMakeOnlyForArrayComparisonWithNonArrayableValues($comparison, $values);
        }

        if (!WpQueryMeta::isOnlyForArraysComparison($comparison) && WpQueryMeta::isArrayableValue($values)) {
            throw new TryingToMakeNonArrayComparisonWithArrayableValues($comparison, $values);
        }
    }
}
