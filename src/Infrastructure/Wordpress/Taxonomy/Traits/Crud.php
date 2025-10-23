<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits;

use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions\InitializingModelWithWrongTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\TermInstantiationError;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Exceptions\FailedToFindOrCreate;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Create;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Create\Exceptions\FailedToRetrieveNewTermId;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Create\Exceptions\InsertTermError;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Delete;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Read;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Update;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Exceptions\FailedToFind;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidModelClass;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder;

/**
 * @mixin Repository
 */
trait Crud
{
    use Create;
    use Read;
    use Update;
    use Delete;

    /**
     * @param string $term_name
     * @return static
     * @throws FailedToFindOrCreate
     * @throws TermInstantiationError
     */
    public static function findOrCreate(string $term_name): static
    {
        try {
            return static::findByName($term_name) ?? new static(static::create($term_name));
        } catch (FailedToFind
        |FailedToRetrieveNewTermId
        |InitializingModelWithWrongTaxonomyName
        |InsertTermError $exception) {
            throw new FailedToFindOrCreate($term_name, static::getNameKey(), $exception);
        }
    }

    /**
     * @return TermModelQueryBuilder
     * @throws InvalidModelClass
     */
    public static function query(): TermModelQueryBuilder
    {
        return TermModelQueryBuilder::make(static::class);
    }
}
