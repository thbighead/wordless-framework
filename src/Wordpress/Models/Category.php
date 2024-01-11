<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;
use Wordless\Wordpress\Models\Category\Dictionary;

class Category extends Taxonomy
{
    final protected const NAME_KEY = StandardTaxonomy::category->name;

    protected static function getDictionary(): Dictionary
    {
        return Dictionary::getInstance();
    }
}
