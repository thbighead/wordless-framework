<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\FailedToInstantiateParent;
use Wordless\Wordpress\Models\Category\Dictionary;

class Category extends Taxonomy
{
    final protected const UNCATEGORIZED_SLUG = 'uncategorized';

    final protected const NAME_KEY = StandardTaxonomy::category->value;

    protected static function getDictionary(): Dictionary
    {
        return Dictionary::getInstance();
    }

    /**
     * @return bool
     * @throws FailedToInstantiateParent
     */
    public function isSubcategory(): bool
    {
        return $this->hasParent();
    }

    public function isUncategorized(): bool
    {
        return $this->slug === self::UNCATEGORIZED_SLUG;
    }
}
