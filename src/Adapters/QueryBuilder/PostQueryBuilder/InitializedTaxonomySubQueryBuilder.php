<?php

namespace Wordless\Adapters\QueryBuilder\PostQueryBuilder;

use Wordless\Contracts\Adapter\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\AndWhereClauses;
use Wordless\Contracts\Adapter\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\OrWhereClauses;

class InitializedTaxonomySubQueryBuilder extends TaxonomySubQueryBuilder
{
    use AndWhereClauses, OrWhereClauses;

    public function __construct(array $taxonomy_sub_query_arguments)
    {
        $this->taxonomy_sub_query_arguments = $taxonomy_sub_query_arguments;
    }
}
