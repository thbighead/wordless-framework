<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;
use Wordless\Wordpress\Models\Category\Dictionary;
use Wordless\Wordpress\Models\Category\Traits\Repository;

class Category extends Taxonomy
{
    use Repository;

    final protected const NAME_KEY = StandardTaxonomy::category->name;

    protected static function getDictionary(): Dictionary
    {
        return Dictionary::getInstance();
    }

    public function isUncategorized(): bool
    {
        return $this->slug === 'uncategorized';
    }
}
