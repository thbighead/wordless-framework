<?php

namespace Wordless\Adapters\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;

use Wordless\Abstractions\Enums\WpQueryTaxonomy;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;
use Wordless\Contracts\Adapter\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\AndWhereClauses;

class NestedAnd extends TaxonomySubQueryBuilder
{
    use AndWhereClauses;

    public function __construct(array $taxonomy_sub_query_arguments)
    {
        $this->taxonomy_sub_query_arguments = $taxonomy_sub_query_arguments;
        $this->taxonomy_sub_query_arguments[WpQueryTaxonomy::KEY_RELATION] = WpQueryTaxonomy::RELATION_AND;
    }
}
