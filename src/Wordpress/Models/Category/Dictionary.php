<?php

namespace Wordless\Wordpress\Models\Category;

use Wordless\Infrastructure\Wordpress\Taxonomy\Dictionary as BaseDictionary;

class Dictionary extends BaseDictionary
{
    protected function __construct()
    {
        parent::__construct('category');
    }
}
