<?php

namespace Wordless\Adapters\QueryBuilder\PostQueryBuilder;

use Closure;
use Wordless\Abstractions\Enums\WpQueryTaxonomy;
use Wordless\Exceptions\UnexpectedTaxonomySubQueryClosureReturn;

abstract class TaxonomySubQueryBuilder
{
    protected array $taxonomy_sub_query_arguments = [];

    public function build(): array
    {
        return $this->taxonomy_sub_query_arguments;
    }

    protected function resolveClosure(callable $closure): array
    {
        $result = $closure(new EmptyTaxonomySubQueryBuilder);

        if ($result instanceof TaxonomySubQueryBuilder) {
            $result = $result->build();
        }

        if (!is_array($result)) {
            throw new UnexpectedTaxonomySubQueryClosureReturn($result);
        }

        return $result;
    }

    protected function setConditionToSubQuery(array $condition)
    {
        $this->taxonomy_sub_query_arguments[] = $condition;
    }

    /**
     * @param string $taxonomy
     * @param string $column
     * @param string $operator
     * @param int|string|int[]|string[] $values
     * @param bool $include_children
     * @return array<string, mixed>
     */
    protected function mountCondition(
        string $taxonomy,
        string $column,
        string $operator,
               $values,
        bool   $include_children = true
    ): array
    {
        return [
            WpQueryTaxonomy::KEY_TAXONOMY => $taxonomy,
            WpQueryTaxonomy::KEY_TERMS => $values,
            WpQueryTaxonomy::KEY_OPERATOR => $operator,
            WpQueryTaxonomy::KEY_COLUMN => $column,
            WpQueryTaxonomy::KEY_INCLUDE_CHILDREN => $include_children,
        ];
    }
}
