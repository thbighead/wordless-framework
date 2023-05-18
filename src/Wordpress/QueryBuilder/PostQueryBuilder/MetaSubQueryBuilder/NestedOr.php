<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;

use Closure;
use Wordless\Enums\WpQueryTaxonomy;
use Wordless\Infrastructure\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;

class NestedOr extends MetaSubQueryBuilder
{
    public function __construct(array $taxonomy_sub_query_arguments)
    {
        $this->taxonomy_sub_query_arguments = $taxonomy_sub_query_arguments;
        $this->taxonomy_sub_query_arguments[WpQueryTaxonomy::KEY_RELATION] = WpQueryTaxonomy::RELATION_OR;
    }

    /**
     * @param Closure $nestedSubQuery
     * @return $this
     */
    public function orWhereTaxonomy(Closure $nestedSubQuery): NestedOr
    {
        $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($nestedSubQuery);

        return $this;
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return $this
     */
    public function orWhereTaxonomyExists(
        string $taxonomy,
        string $column,
               $values,
        bool   $include_children = true
    ): NestedOr
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
    public function orWhereTaxonomyIn(
        string $taxonomy,
        string $column,
               $values,
        bool   $include_children = true
    ): NestedOr
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
    public function orWhereTaxonomyIs(
        string $taxonomy,
        string $column,
               $values,
        bool   $include_children = true
    ): NestedOr
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
    public function orWhereTaxonomyNotExists(
        string $taxonomy,
        string $column,
               $values,
        bool   $include_children = true
    ): NestedOr
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
    public function orWhereTaxonomyNotIn(
        string $taxonomy,
        string $column,
               $values,
        bool   $include_children = true
    ): NestedOr
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
