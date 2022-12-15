<?php

namespace Wordless\Adapters\QueryBuilder\PostQueryBuilder;

use Wordless\Abstractions\Enums\WpQueryTaxonomy;
use Wordless\Exceptions\TryingToBuildEmptySubQuery;

class EmptyTaxonomySubQueryBuilder extends TaxonomySubQueryBuilder
{
    /**
     * @return array
     * @throws TryingToBuildEmptySubQuery
     */
    public function build(): array
    {
        throw new TryingToBuildEmptySubQuery;
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return InitializedTaxonomySubQueryBuilder
     */
    public function whereTaxonomyExists(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): InitializedTaxonomySubQueryBuilder
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_EXISTS,
            $column,
            $include_children,
        ));

        return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return InitializedTaxonomySubQueryBuilder
     */
    public function whereTaxonomyIn(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): InitializedTaxonomySubQueryBuilder
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_IN,
            $column,
            $include_children,
        ));

        return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return InitializedTaxonomySubQueryBuilder
     */
    public function whereTaxonomyIs(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): InitializedTaxonomySubQueryBuilder
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_AND,
            $column,
            $include_children,
        ));

        return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return InitializedTaxonomySubQueryBuilder
     */
    public function whereTaxonomyNotExists(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): InitializedTaxonomySubQueryBuilder
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_NOT_EXISTS,
            $column,
            $include_children,
        ));

        return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string|callable $taxonomy
     * @param string|null $column
     * @param int|string|int[]|string[]|null $values
     * @param bool $include_children
     * @return InitializedTaxonomySubQueryBuilder
     */
    public function whereTaxonomyNotIn(
        $taxonomy,
        ?string $column = null,
        $values = null,
        bool $include_children = true
    ): InitializedTaxonomySubQueryBuilder
    {
        if (is_callable($taxonomy)) {
            $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($taxonomy);

            return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
        }

        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $values,
            WpQueryTaxonomy::OPERATOR_NOT_IN,
            $column,
            $include_children,
        ));

        return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
    }
}
