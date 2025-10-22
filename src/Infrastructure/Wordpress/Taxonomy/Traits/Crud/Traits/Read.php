<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud;
use Wordless\Wordpress\Models\Category;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidModelClass;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder;

/**
 * @mixin Crud
 */
trait Read
{
    /**
     * @return bool
     * @throws InvalidModelClass
     * @throws EmptyQueryBuilderArguments
     */
    public function noneCreated(): bool
    {
        if (static::getNameKey() === StandardTaxonomy::category->value) {
            return $this->resolveNoneCreatedForCategory();
        }

        return static::query()->count() > 0;
    }

    private function resolveNoneCreatedForCategory(): bool
    {
        $query = static::query();

        if ($uncategorized = Category::findBySlug(self::UNCATEGORIZED_SLUG)) {
            $query->except($uncategorized);
        }

        return $query->count() > 0;
    }
}
