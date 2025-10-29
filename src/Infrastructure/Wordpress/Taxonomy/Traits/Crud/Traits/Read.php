<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\TermInstantiationError;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Read\Exceptions\CouldNotResolveNoneCreated;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Read\Exceptions\CouldNotResolveNoneCreatedForCategory;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Exceptions\FailedToFind;
use Wordless\Wordpress\Models\Category;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidModelClass;

/**
 * @mixin Crud
 */
trait Read
{
    /**
     * @return bool
     * @throws CouldNotResolveNoneCreated
     * @throws CouldNotResolveNoneCreatedForCategory
     */
    public static function noneCreated(): bool
    {
        if (static::getNameKey() === StandardTaxonomy::category->value) {
            return self::resolveNoneCreatedForCategory();
        }

        try {
            return static::query()->count() > 0;
        } catch (EmptyQueryBuilderArguments|InvalidModelClass $exception) {
            throw new CouldNotResolveNoneCreated(static::getNameKey(), $exception);
        }
    }

    /**
     * @return bool
     * @throws CouldNotResolveNoneCreatedForCategory
     */
    private static function resolveNoneCreatedForCategory(): bool
    {
        try {
            $query = static::query();

            if ($uncategorized = Category::findBySlug(self::UNCATEGORIZED_SLUG)) {
                $query->except($uncategorized);
            }

            return $query->count() > 0;
        } catch (EmptyQueryBuilderArguments|FailedToFind|InvalidModelClass|TermInstantiationError $exception) {
            throw new CouldNotResolveNoneCreatedForCategory($exception);
        }
    }
}
