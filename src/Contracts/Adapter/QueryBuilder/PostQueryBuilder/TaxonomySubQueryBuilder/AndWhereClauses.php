<?php

namespace Wordless\Contracts\Adapter\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;

use Wordless\Abstractions\Enums\WpQueryTaxonomy;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\NestedAnd;

trait AndWhereClauses
{
    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return NestedAnd
     */
    public function andWhereTaxonomyExists(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): NestedAnd
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new NestedAnd($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_EXISTS,
            $column,
            $include_children,
        ));

        return new NestedAnd($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return NestedAnd
     */
    public function andWhereTaxonomyIn(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): NestedAnd
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new NestedAnd($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_IN,
            $column,
            $include_children,
        ));

        return new NestedAnd($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return NestedAnd
     */
    public function andWhereTaxonomyIs(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): NestedAnd
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new NestedAnd($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_AND,
            $column,
            $include_children,
        ));

        return new NestedAnd($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return NestedAnd
     */
    public function andWhereTaxonomyNotExists(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): NestedAnd
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new NestedAnd($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_NOT_EXISTS,
            $column,
            $include_children,
        ));

        return new NestedAnd($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return NestedAnd
     */
    public function andWhereTaxonomyNotIn(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): NestedAnd
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new NestedAnd($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_NOT_IN,
            $column,
            $include_children,
        ));

        return new NestedAnd($this->taxonomy_sub_query_arguments);
    }
}
