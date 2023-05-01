<?php

namespace Wordless\Adapters\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;

use Closure;
use Wordless\Abstractions\Enums\WpQueryMeta;
use Wordless\Abstractions\Enums\WpQueryTaxonomy;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;

class NestedAnd extends MetaSubQueryBuilder
{
    public function __construct(array $meta_sub_query_arguments)
    {
        $this->meta_sub_query_arguments = $meta_sub_query_arguments;
        $this->meta_sub_query_arguments[WpQueryMeta::KEY_RELATION] = WpQueryMeta::RELATION_AND;
    }

    /**
     * @param Closure $nestedSubQuery
     * @return $this
     */
    public function andWhereTaxonomy(Closure $nestedSubQuery): NestedAnd
    {
        $this->meta_sub_query_arguments[] = $this->resolveClosure($nestedSubQuery);

        return $this;
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return $this
     */
    public function andWhereTaxonomyExists(
        string $taxonomy,
        string $column,
               $values,
        bool $include_children = true
    ): NestedAnd
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_EXISTS,
            $values,
            $include_children,
        ));

        return $this;
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return $this
     */
    public function andWhereTaxonomyIn(
        string $taxonomy,
        string $column,
               $values,
        bool $include_children = true
    ): NestedAnd
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_IN,
            $values,
            $include_children,
        ));

        return $this;
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return $this
     */
    public function andWhereTaxonomyIs(
        string $taxonomy,
        string $column,
               $values,
        bool $include_children = true
    ): NestedAnd
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_AND,
            $values,
            $include_children,
        ));

        return $this;
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return $this
     */
    public function andWhereTaxonomyNotExists(
        string $taxonomy,
        string $column,
               $values,
        bool $include_children = true
    ): NestedAnd
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_NOT_EXISTS,
            $values,
            $include_children,
        ));

        return $this;
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return $this
     */
    public function andWhereTaxonomyNotIn(
        string $taxonomy,
        string $column,
               $values,
        bool $include_children = true
    ): NestedAnd
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_NOT_IN,
            $values,
            $include_children,
        ));

        return $this;
    }
}
