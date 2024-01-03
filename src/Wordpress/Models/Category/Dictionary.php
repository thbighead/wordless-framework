<?php

namespace Wordless\Wordpress\Models\Category;

use Wordless\Infrastructure\Wordpress\Taxonomy\Dictionary as BaseDictionary;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;

class Dictionary extends BaseDictionary
{
    protected function __construct()
    {
        parent::__construct(StandardTaxonomy::category->name);
    }
}
