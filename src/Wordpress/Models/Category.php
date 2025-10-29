<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Generator;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions\InitializingModelWithWrongTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\FailedToInstantiateParent;
use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\TermInstantiationError;
use Wordless\Wordpress\Models\Category\Dictionary;

class Category extends Taxonomy
{
    final protected const UNCATEGORIZED_SLUG = 'uncategorized';

    final protected const NAME_KEY = StandardTaxonomy::category->value;

    /**
     * @return Generator<static>
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws TermInstantiationError
     */
    public static function all(): Generator
    {
        return parent::all();
    }

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
