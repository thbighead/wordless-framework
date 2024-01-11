<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Closure;
use Wordless\Application\Helpers\GetType;
use Wordless\Application\Helpers\Log;
use Wordless\Enums\WpQueryMeta;
use Wordless\Exceptions\TryingToMakeNonArrayComparisonWithArrayableValues;
use Wordless\Exceptions\TryingToMakeOnlyForArrayComparisonWithNonArrayableValues;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\CannotBuild;

class EmptyMetaSubQueryBuilder extends MetaSubQueryBuilder
{
    use CannotBuild;

    public function whereMeta(Closure $nestedSubQuery): InitializedMetaSubQueryBuilder
    {
        $this->meta_sub_query_arguments[] = $this->resolveClosure($nestedSubQuery);

        return new InitializedMetaSubQueryBuilder($this->meta_sub_query_arguments);
    }

    /**
     * @param string $meta_key
     * @param mixed $meta_value
     * @param string|null $value_typed_as
     * @return InitializedMetaSubQueryBuilder
     * @throws TryingToMakeNonArrayComparisonWithArrayableValues
     * @throws TryingToMakeOnlyForArrayComparisonWithNonArrayableValues
     */
    public function whereMetaEqualsTo(
        string  $meta_key,
                $meta_value,
        ?string $value_typed_as = null
    ): InitializedMetaSubQueryBuilder
    {
        return $this->whereMetaIs(
            $meta_key,
            WpQueryMeta::COMPARE_EQUAL,
            $meta_value,
            $value_typed_as ?? $this->guessMetaValueComparisonType($meta_value)
        );
    }

    /**
     * @param string $meta_key
     * @param mixed $meta_values
     * @return InitializedMetaSubQueryBuilder
     * @throws TryingToMakeNonArrayComparisonWithArrayableValues
     * @throws TryingToMakeOnlyForArrayComparisonWithNonArrayableValues
     */
    public function whereMetaExists(string $meta_key, $meta_values = []): InitializedMetaSubQueryBuilder
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $meta_key,
            WpQueryMeta::COMPARE_EXISTS,
            $meta_values
        ));

        return new InitializedMetaSubQueryBuilder($this->meta_sub_query_arguments);
    }

    /**
     * @param string $meta_key
     * @param array $meta_values
     * @param string $value_typed_as
     * @return InitializedMetaSubQueryBuilder
     * @throws TryingToMakeNonArrayComparisonWithArrayableValues
     */
    public function whereMetaIn(
        string $meta_key,
        array  $meta_values,
        string $value_typed_as = WpQueryMeta::TYPE_CHAR
    ): InitializedMetaSubQueryBuilder
    {
        try {
            $this->setConditionToSubQuery($this->mountCondition(
                $meta_key,
                WpQueryMeta::COMPARE_IN,
                $meta_values,
                $value_typed_as
            ));
        } catch (TryingToMakeOnlyForArrayComparisonWithNonArrayableValues $exception) {
            Log::impossibleException($exception);
        }

        return new InitializedMetaSubQueryBuilder($this->meta_sub_query_arguments);
    }

    /**
     * @param string $meta_key
     * @param string $comparison
     * @param $meta_value
     * @param string $value_typed_as
     * @return InitializedMetaSubQueryBuilder
     * @throws TryingToMakeOnlyForArrayComparisonWithNonArrayableValues
     * @throws TryingToMakeNonArrayComparisonWithArrayableValues
     */
    public function whereMetaIs(
        string $meta_key,
        string $comparison,
               $meta_value,
        string $value_typed_as = WpQueryMeta::TYPE_CHAR
    ): InitializedMetaSubQueryBuilder
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $meta_key,
            $comparison,
            $meta_value,
            $value_typed_as,
        ));

        return new InitializedMetaSubQueryBuilder($this->meta_sub_query_arguments);
    }

    /**
     * @param string $meta_key
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return InitializedMetaSubQueryBuilder
     */
    public function whereMetaNotExists(
        string $meta_key,
        string $column,
               $values,
        bool   $include_children = true
    ): InitializedMetaSubQueryBuilder
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $meta_key,
            $column,
            WpQueryMeta::COMPARE_NOT_EXISTS,
            $values,
            $include_children,
        ));

        return new InitializedMetaSubQueryBuilder($this->meta_sub_query_arguments);
    }

    /**
     * @param string $meta_key
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return InitializedMetaSubQueryBuilder
     */
    public function whereMetaNotIn(
        string $meta_key,
        string $column,
               $values,
        bool   $include_children = true
    ): InitializedMetaSubQueryBuilder
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $meta_key,
            $column,
            WpQueryMeta::COMPARE_NOT_IN,
            $values,
            $include_children,
        ));

        return new InitializedMetaSubQueryBuilder($this->meta_sub_query_arguments);
    }

    protected function mountKeyCompare(): string
    {
        return WpQueryMeta::META_PREFIX . WpQueryMeta::KEY_COMPARE;
    }

    protected function mountKeyMetaKey(): string
    {
        return WpQueryMeta::META_PREFIX . WpQueryMeta::KEY_META_KEY;
    }

    protected function mountKeyMetaValue(): string
    {
        return WpQueryMeta::META_PREFIX . WpQueryMeta::KEY_META_VALUE;
    }

    protected function mountKeyValueType(): string
    {
        return WpQueryMeta::META_PREFIX . WpQueryMeta::KEY_VALUE_TYPE;
    }

    private function guessMetaValueComparisonType($meta_value): string
    {
        switch (true) {
            case is_bool($meta_value):
                return WpQueryMeta::TYPE_BINARY;
            case is_int($meta_value):
                return $meta_value > 0 ? WpQueryMeta::TYPE_UNSIGNED : WpQueryMeta::TYPE_SIGNED;
            case is_float($meta_value):
                return WpQueryMeta::TYPE_DECIMAL;
            case GetType::isDateable($meta_value):
                return WpQueryMeta::TYPE_DATETIME;
            case is_numeric($meta_value):
                return WpQueryMeta::TYPE_NUMERIC;
            default:
                return WpQueryMeta::TYPE_CHAR;
        }
    }
}
