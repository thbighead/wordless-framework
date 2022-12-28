<?php

namespace Wordless\Adapters;

use Wordless\Abstractions\TaxonomyTermsList;
use Wordless\Contracts\Adapter\CustomTaxonomy\Register;
use Wordless\Exceptions\TaxonomyNotRegistered;
use WP_Taxonomy;
use WP_Term;

/**
 * @mixin WP_Taxonomy
 */
abstract class Taxonomy
{
    use Register;

    /** @var self[] $taxonomies */
    private static array $taxonomies = [];
    /** @var TaxonomyTermsList[] $taxonomyTerms */
    private static array $taxonomyTerms = [];

    public const TAXONOMY_NAME_MAX_LENGTH = 32;
    protected const NAME = null;

    private WP_Taxonomy $wpTaxonomy;

    public static function find(string $taxonomy): ?self
    {
        return self::$taxonomies[$taxonomy] ?? null;
    }

    /**
     * @param string|int $taxonomy_term
     * @return WP_Term|null
     */
    public static function findTerm($taxonomy_term): ?WP_Term
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
            } catch (TaxonomyNotRegistered $exception) {
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
        return self::$taxonomyTerms[static::NAME] ??
            self::$taxonomyTerms[static::NAME] = new TaxonomyTermsList(static::NAME);
    }

    public function __call(string $method_name, array $arguments)
    {
        return $this->wpTaxonomy->$method_name(...$arguments);
    }

    /**
     * @param WP_Taxonomy|string $taxonomy
     */
    public function __construct($taxonomy)
    {
        if ($taxonomy instanceof WP_Taxonomy) {
            $this->wpTaxonomy = $taxonomy;

            return;
        }

        $this->wpTaxonomy = get_taxonomy($taxonomy) ?: null;
    }

    public function __get(string $attribute)
    {
        return $this->wpTaxonomy->$attribute;
    }
}
