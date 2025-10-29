<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Create\Exceptions\FailedToRetrieveNewTermId;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Create\Exceptions\InsertTermError;
use WP_Error;
use WP_Term;

trait Create
{
    final public const TERM_ID_RESULT_KEY = 'term_id';

    /**
     * @param string $name
     * @param string|null $description
     * @param string|null $custom_slug
     * @param Taxonomy|WP_Term|int|null $parent
     * @return int
     * @throws FailedToRetrieveNewTermId
     * @throws InsertTermError
     */
    public static function create(
        string                    $name,
        ?string                   $description = null,
        ?string                   $custom_slug = null,
        Taxonomy|WP_Term|int|null $parent = null
    ): int
    {
        $additional_arguments = [];

        if (!empty($description)) {
            $additional_arguments['description'] = $description;
        }

        if (!empty($custom_slug)) {
            $additional_arguments['slug'] = $custom_slug;
        }

        if (!is_null($parent = self::prepareParent($parent))) {
            $additional_arguments['parent'] = $parent;
        }

        if (($result = wp_insert_term($name, static::getNameKey(), $additional_arguments)) instanceof WP_Error) {
            throw new InsertTermError($result);
        }

        return (int)($result[self::TERM_ID_RESULT_KEY] ?? throw new FailedToRetrieveNewTermId($name, $result));
    }

    /**
     * @param array<string, string|array> $hierarchy
     * @return array<int, int|array>
     * @throws FailedToRetrieveNewTermId
     * @throws InsertTermError
     */
    public static function createHierarchically(array $hierarchy): array
    {
        return self::createRecursively($hierarchy);
    }

    /**
     * @param array $siblings
     * @param int|null $parent
     * @return array<int, int|array>
     * @throws FailedToRetrieveNewTermId
     * @throws InsertTermError
     */
    private static function createRecursively(array $siblings, ?int $parent = null): array
    {
        $ids = [];

        foreach ($siblings as $key => $value) {
            if (!is_string($key)) {
                $ids[] = static::create($value, $parent);
                continue;
            }

            $ids[$parent] = self::createRecursively($value, static::create($key, $parent));
        }

        return $ids;
    }

    private static function prepareParent(Taxonomy|WP_Term|int|null $parent): ?int
    {
        if ($parent === null) {
            return null;
        }

        if (!is_int($parent)) {
            return $parent->term_id;
        }

        return $parent > 0 ? $parent : null;
    }
}
