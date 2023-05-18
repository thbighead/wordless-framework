<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Closure;
use Wordless\Enums\WpQueryTaxonomy;
use Wordless\Exceptions\TryingToBuildEmptySubQuery;
use Wordless\Infrastructure\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;

class EmptyTaxonomySubQueryBuilder extends TaxonomySubQueryBuilder
{
    /**
     * @return array
     * @throws TryingToBuildEmptySubQuery
     */
    public function build(): array
    {
        throw new TryingToBuildEmptySubQuery(self::class);
    }

    public function whereTaxonomy(Closure $nestedSubQuery): InitializedTaxonomySubQueryBuilder
    {
        $this->taxonomy_sub_query_arguments[] = $this->resolveClosure($nestedSubQuery);

        return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return InitializedTaxonomySubQueryBuilder
     */
    public function whereTaxonomyExists(
        string $taxonomy,
        string $column,
        $values,
        bool $include_children = true
    ): InitializedTaxonomySubQueryBuilder
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_EXISTS,
            $values,
            $include_children,
        ));

        return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return InitializedTaxonomySubQueryBuilder
     */
    public function whereTaxonomyIn(
        string $taxonomy,
        string $column,
        $values,
        bool $include_children = true
    ): InitializedTaxonomySubQueryBuilder
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_IN,
            $values,
            $include_children,
        ));

        return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return InitializedTaxonomySubQueryBuilder
     */
    public function whereTaxonomyIs(
        string $taxonomy,
        string $column,
        $values,
        bool $include_children = true
    ): InitializedTaxonomySubQueryBuilder
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_AND,
            $values,
            $include_children,
        ));

        return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return InitializedTaxonomySubQueryBuilder
     */
    public function whereTaxonomyNotExists(
        string $taxonomy,
        string $column,
        $values,
        bool $include_children = true
    ): InitializedTaxonomySubQueryBuilder
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_NOT_EXISTS,
            $values,
            $include_children,
        ));

        return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return InitializedTaxonomySubQueryBuilder
     */
    public function whereTaxonomyNotIn(
        string $taxonomy,
        string $column,
        $values,
        bool $include_children = true
    ): InitializedTaxonomySubQueryBuilder
    {
        $this->setConditionToSubQuery($this->mountCondition(
            $taxonomy,
            $column,
            WpQueryTaxonomy::OPERATOR_NOT_IN,
            $values,
            $include_children,
        ));

        return new InitializedTaxonomySubQueryBuilder($this->taxonomy_sub_query_arguments);
    }
}
