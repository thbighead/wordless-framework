<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits;

use WP_Term;

trait Repository
{
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
