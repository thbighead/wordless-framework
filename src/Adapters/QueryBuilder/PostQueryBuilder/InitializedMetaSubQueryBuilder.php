<?php

namespace Wordless\Adapters\QueryBuilder\PostQueryBuilder;

use Closure;
use Wordless\Abstractions\Enums\WpQueryTaxonomy;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\NestedAnd;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\NestedOr;

class InitializedMetaSubQueryBuilder extends TaxonomySubQueryBuilder
{
    public function __construct(array $taxonomy_sub_query_arguments)
    {
        $this->taxonomy_sub_query_arguments = $taxonomy_sub_query_arguments;
    }

    public function andWhereTaxonomy(Closure $nestedSubQuery): NestedAnd
    {
        $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($nestedSubQuery);

        return new NestedAnd($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return NestedAnd
     */
    public function andWhereTaxonomyExists(
        string $taxonomy,
        string $column,
               $values,
        bool   $include_children = true
    ): NestedAnd
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_EXISTS,
            $values,
            $include_children,
        ));

        return new NestedAnd($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return NestedAnd
     */
    public function andWhereTaxonomyIn(
        string $taxonomy,
        string $column,
               $values,
        bool   $include_children = true
    ): NestedAnd
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_IN,
            $values,
            $include_children,
        ));

        return new NestedAnd($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return NestedAnd
     */
    public function andWhereTaxonomyIs(
        string $taxonomy,
        string $column,
               $values,
        bool   $include_children = true
    ): NestedAnd
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_AND,
            $values,
            $include_children,
        ));

        return new NestedAnd($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return NestedAnd
     */
    public function andWhereTaxonomyNotExists(
        string $taxonomy,
        string $column,
               $values,
        bool   $include_children = true
    ): NestedAnd
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_NOT_EXISTS,
            $values,
            $include_children,
        ));

        return new NestedAnd($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return NestedAnd
     */
    public function andWhereTaxonomyNotIn(
        string $taxonomy,
        string $column,
               $values,
        bool   $include_children = true
    ): NestedAnd
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_NOT_IN,
            $values,
            $include_children,
        ));

        return new NestedAnd($this->taxonomy_sub_query_arguments);
    }

    public function orWhereTaxonomy(Closure $nestedSubQuery): NestedOr
    {
        $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($nestedSubQuery);

        return new NestedOr($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return NestedOr
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

        return new NestedOr($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return NestedOr
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

        return new NestedOr($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return NestedOr
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

        return new NestedOr($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return NestedOr
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

        return new NestedOr($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return NestedOr
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

        return new NestedOr($this->taxonomy_sub_query_arguments);
    }
}
