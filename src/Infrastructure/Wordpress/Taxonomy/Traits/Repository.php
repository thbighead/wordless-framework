<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits;

use Generator;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions\InitializingModelWithWrongTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\TermInstantiationError;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Enums\Field;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Exceptions\FailedToFind;
use WP_Term;

/**
 * @mixin Taxonomy
 */
trait Repository
{
    /**
     * @return Generator<static>
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws TermInstantiationError
     */
    public static function all(): Generator
    {
        foreach (static::getDictionary()->all() as $term) {
            yield new static($term);
        }
    }

    public static function count(): int
    {
        return count(static::getDictionary()->all());
    }

    /**
     * @param int|string $term
     * @return static|null
     * @throws FailedToFind
     * @throws TermInstantiationError
     */
    public static function find(int|string $term): ?static
    {
        if (is_int($term) || is_numeric($term)) {
            return static::findById((int)$term);
        }

        return static::findBySlug($term) ?? static::findByName($term);
    }

    /**
     * @param Field $field
     * @param int|string $value
     * @return static|null
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws TermInstantiationError
     */
    public static function findBy(Field $field, int|string $value): ?static
    {
        if (!(($term = get_term_by($field->name, $value, static::getNameKey())) instanceof WP_Term)) {
            return null;
        }

        return new static($term);
    }

    /**
     * @param int $id
     * @return static|null
     * @throws FailedToFind
     * @throws TermInstantiationError
     */
    public static function findById(int $id): ?static
    {
        try {
            $term = static::findBy(Field::term_id, $id);

            if (!self::areEquals($term, static::getById($id))) {
                static::getDictionary()->reload();
            }

            return $term;
        } catch (EmptyQueryBuilderArguments|InitializingModelWithWrongTaxonomyName $exception) {
            throw new FailedToFind($id, $exception);
        }
    }

    /**
     * @param string $name
     * @return static|null
     * @throws FailedToFind
     * @throws TermInstantiationError
     */
    public static function findByName(string $name): ?static
    {
        try {
            $term = static::findBy(Field::name, $name);

            if (!self::areEquals($term, static::getByName($name))) {
                static::getDictionary()->reload();
            }

            return $term;
        } catch (EmptyQueryBuilderArguments|InitializingModelWithWrongTaxonomyName $exception) {
            throw new FailedToFind($name, $exception);
        }
    }

    /**
     * @param string $slug
     * @return static|null
     * @throws FailedToFind
     * @throws TermInstantiationError
     */
    public static function findBySlug(string $slug): ?static
    {
        try {
            $term = static::findBy(Field::name, $slug);

            if (!self::areEquals($term, static::getBySlug($slug))) {
                static::getDictionary()->reload();
            }

            return $term;
        } catch (EmptyQueryBuilderArguments|InitializingModelWithWrongTaxonomyName $exception) {
            throw new FailedToFind($slug, $exception);
        }
    }

    /**
     * @param int|string $term
     * @return static|null
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws TermInstantiationError
     */
    public static function get(int|string $term): ?static
    {
        if (is_int($term) || is_numeric($term)) {
            return static::getById((int)$term);
        }

        return static::getBySlug($term) ?? static::getByName($term);
    }

    /**
     * @param int $id
     * @return static|null
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws TermInstantiationError
     */
    public static function getById(int $id): ?static
    {
        $term = static::getDictionary()->getById($id);

        return $term === null ? null : new static($term);
    }

    /**
     * @param string $name
     * @return static|null
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws TermInstantiationError
     */
    public static function getByName(string $name): ?static
    {
        $term = static::getDictionary()->getByName($name);

        return $term === null ? null : new static($term);
    }

    /**
     * @param string $slug
     * @return static|null
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws TermInstantiationError
     */
    public static function getBySlug(string $slug): ?static
    {
        $term = static::getDictionary()->getBySlug($slug);

        return $term === null ? null : new static($term);
    }

    private static function areEquals(?Taxonomy $term1, ?Taxonomy $term2): bool
    {
        return $term1?->term_id === $term2?->term_id
            && $term1?->term_taxonomy_id === $term2?->term_taxonomy_id
            && $term1?->slug === $term2?->slug
            && $term1?->name === $term2?->name;
    }
}
