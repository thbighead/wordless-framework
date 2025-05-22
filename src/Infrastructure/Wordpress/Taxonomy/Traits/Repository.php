<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits;

use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits\Create;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits\Create\Exceptions\FailedToRetrieveNewTermId;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits\Create\Exceptions\InsertTermError;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits\Delete;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits\Update;
use WP_Term;

trait Repository
{
    use Create;
    use Update;
    use Delete;

    /**
     * @return WP_Term[]
     */
    public static function all(): array
    {
        return static::getDictionary()->all();
    }

    public static function find(int|string $term): ?WP_Term
    {
        if (is_int($term) || is_numeric($term)) {
            return static::getById((int)$term);
        }

        return static::getBySlug($term) ?? static::getByName($term);
    }

    /**
     * @param string $term_name
     * @return static
     * @throws FailedToRetrieveNewTermId
     * @throws InsertTermError
     */
    public static function findOrCreate(string $term_name): static
    {
        return new static(static::getByName($term_name) ?? static::create($term_name));
    }

    public static function getById(int $id): ?WP_Term
    {
        return static::getDictionary()->getById($id);
    }

    public static function getByName(string $name): ?WP_Term
    {
        return static::getDictionary()->getByName($name);
    }

    public static function getBySlug(string $slug): ?WP_Term
    {
        return static::getDictionary()->getBySlug($slug);
    }
}
