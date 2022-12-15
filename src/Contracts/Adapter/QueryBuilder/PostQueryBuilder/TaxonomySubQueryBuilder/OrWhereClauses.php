<?php

namespace Wordless\Contracts\Adapter\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;

use Wordless\Abstractions\Enums\WpQueryTaxonomy;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\NestedOr;

trait OrWhereClauses
{
    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return NestedOr
     */
    public function orWhereTaxonomyExists(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): NestedOr
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new NestedOr($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_EXISTS,
            $column,
            $include_children,
        ));

        return new NestedOr($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return NestedOr
     */
    public function orWhereTaxonomyIn(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): NestedOr
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new NestedOr($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_IN,
            $column,
            $include_children,
        ));

        return new NestedOr($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return NestedOr
     */
    public function orWhereTaxonomyIs(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): NestedOr
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new NestedOr($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_AND,
            $column,
            $include_children,
        ));

        return new NestedOr($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return NestedOr
     */
    public function orWhereTaxonomyNotExists(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): NestedOr
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new NestedOr($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_NOT_EXISTS,
            $column,
            $include_children,
        ));

        return new NestedOr($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return NestedOr
     */
    public function orWhereTaxonomyNotIn(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): NestedOr
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new NestedOr($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_NOT_IN,
            $column,
            $include_children,
        ));

        return new NestedOr($this->taxonomy_sub_query_arguments);
    }
}
