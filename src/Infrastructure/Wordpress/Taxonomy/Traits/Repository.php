<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits;

use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Enums\Field;
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
            return static::findById((int)$term);
        }

        return static::findBySlug($term) ?? static::findByName($term);
    }

    public static function findBy(Field $field, int|string $value): ?WP_Term
    {
        if (!(($term = get_term_by($field->name, $value, static::getNameKey())) instanceof WP_Term)) {
            return null;
        }

        return $term;
    }

    public static function findById(int $id): ?WP_Term
    {
        $term = self::findBy(Field::term_id, $id);

        if (!self::areEquals($term, static::getById($id))) {
            static::getDictionary()->reload();
        }

        return $term;
    }

    public static function findByName(string $name): ?WP_Term
    {
        $term = self::findBy(Field::name, $name);

        if (!self::areEquals($term, static::getByName($name))) {
            static::getDictionary()->reload();
        }

        return $term;
    }

    public static function findBySlug(string $slug): ?WP_Term
    {
        $term = self::findBy(Field::name, $slug);

        if (!self::areEquals($term, static::getBySlug($slug))) {
            static::getDictionary()->reload();
        }

        return $term;
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

    public static function get(int|string $term): ?WP_Term
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

    private static function areEquals(?WP_Term $term1, ?WP_Term $term2): bool
    {
        return $term1?->term_id === $term2?->term_id
            && $term1?->term_taxonomy_id === $term2?->term_taxonomy_id
            && $term1?->slug === $term2?->slug
            && $term1?->name === $term2?->name;
    }
}
