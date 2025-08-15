<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use InvalidArgumentException;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions\InitializingModelWithWrongTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;
use Wordless\Wordpress\Models\Category\Dictionary;
use Wordless\Wordpress\Models\Category\Traits\Repository;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

class Category extends Taxonomy
{
    use Repository;

    final protected const NAME_KEY = StandardTaxonomy::category->value;

    protected static function getDictionary(): Dictionary
    {
        return Dictionary::getInstance();
    }

    /**
     * @return bool
     * @throws EmptyQueryBuilderArguments
     * @throws EmptyStringParameter
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws InvalidAcfFunction
     * @throws InvalidArgumentException
     */
    public function isSubcategory(): bool
    {
        return $this->hasParent();
    }

    public function isUncategorized(): bool
    {
        return $this->slug === 'uncategorized';
    }
}
