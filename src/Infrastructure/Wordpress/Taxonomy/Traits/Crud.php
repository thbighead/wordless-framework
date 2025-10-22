<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Create;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Create\Exceptions\FailedToRetrieveNewTermId;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Create\Exceptions\InsertTermError;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Delete;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Read;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Update;
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
     * @throws FailedToRetrieveNewTermId
     * @throws InsertTermError
     * @throws EmptyQueryBuilderArguments
     */
    public static function findOrCreate(string $term_name): static
    {
        return static::findByName($term_name) ?? new static(static::create($term_name));
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
