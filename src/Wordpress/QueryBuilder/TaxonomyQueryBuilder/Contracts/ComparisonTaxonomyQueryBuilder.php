<?php

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts;

use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;

abstract class ComparisonTaxonomyQueryBuilder extends BaseTaxonomyQueryBuilder
{
    public function __construct(TaxonomyQueryBuilder $taxonomyQueryBuilder)
    {
        $this->format = $taxonomyQueryBuilder->getResultFormat();
        $this->arguments = $taxonomyQueryBuilder->getArguments();
    }
}
