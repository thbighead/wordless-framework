<?php

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits;

use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\TaxonomyNotRegistered;
use Wordless\Wordpress\TaxonomyTermsList;
use WP_Term;

trait Repository
{
    public static function find(string $taxonomy): ?self
    {
        return self::$taxonomies[$taxonomy] ?? null;
    }

    /**
     * @param int|string $taxonomy_term
     * @return WP_Term|null
     */
    public static function findTerm(int|string $taxonomy_term): ?WP_Term
    {
        if (is_int($taxonomy_term) || is_numeric($taxonomy_term)) {
            return static::getById((int)$taxonomy_term);
        }

        return static::getBySlug($taxonomy_term) ?? static::getByName($taxonomy_term);
    }

    public static function getAllCustom(): array
    {
        $customTaxonomies = [];

        foreach (get_taxonomies(['_builtin' => false]) as $custom_taxonomy_key) {
            try {
                $customTaxonomies[] = new static($custom_taxonomy_key);
            } catch (TaxonomyNotRegistered) {
                continue;
            }
        }

        return $customTaxonomies;
    }

    public static function getById(int $id): ?WP_Term
    {
        return self::getTaxonomyTermsList()->getById($id);
    }

    public static function getByName(string $name): ?WP_Term
    {
        return self::getTaxonomyTermsList()->getByName($name);
    }

    public static function getBySlug(string $slug): ?WP_Term
    {
        return self::getTaxonomyTermsList()->getBySlug($slug);
    }

    private static function getTaxonomyTermsList(): TaxonomyTermsList
    {
        return self::$taxonomyTerms[static::getNameKey()] ??
            self::$taxonomyTerms[static::getNameKey()] = new TaxonomyTermsList(static::getNameKey());
    }
}
