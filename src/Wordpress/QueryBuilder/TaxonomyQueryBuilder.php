<?php

namespace Wordless\Wordpress\QueryBuilder;

use WP_Taxonomy;

class TaxonomyQueryBuilder
{
    private array $arguments = [];

    public static function getInstance(): static
    {
        return new static;
    }

    public function whereDefault()
    {
        $arguments
    }
}
