<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Dictionary\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder;
use WP_Term;

trait Initializer
{
    /** @var array<string, bool> $loaded */
    private static array $loaded;

    private static function initializeInternalDictionaries(): void
    {
        self::initializeKeyedById();
        self::initializeKeyedByName();
        self::initializeKeyedBySlug();
    }

    private static function initializeKeyedById(): void
    {
        if (!isset(self::$taxonomy_terms_keyed_by_id)) {
            self::$taxonomy_terms_keyed_by_id = [];
        }
    }

    private static function initializeKeyedByName(): void
    {
        if (!isset(self::$taxonomy_terms_keyed_by_name)) {
            self::$taxonomy_terms_keyed_by_name = [];
        }
    }

    private static function initializeKeyedBySlug(): void
    {
        if (!isset(self::$taxonomy_terms_keyed_by_slug)) {
            self::$taxonomy_terms_keyed_by_slug = [];
        }
    }

    private static function initializeLoaded(): void
    {
        if (!isset(self::$loaded)) {
            self::$loaded = [];
        }
    }

    private static function isTaxonomyLoaded(string $taxonomy): bool
    {
        return self::$loaded[$taxonomy] ?? false;
    }

    /**
     * @param string $taxonomy
     * @return void
     * @throws EmptyQueryBuilderArguments
     */
    private static function loadTaxonomyInitializedInternalDictionaries(string $taxonomy): void
    {
        foreach (self::searchTaxonomyTerms($taxonomy) as $taxonomyTerm) {
            self::$taxonomy_terms_keyed_by_id[$taxonomy][$taxonomyTerm->term_id] = $taxonomyTerm;
            self::$taxonomy_terms_keyed_by_name[$taxonomy][$taxonomyTerm->name] =
            self::$taxonomy_terms_keyed_by_slug[$taxonomy][$taxonomyTerm->slug] =
            &self::$taxonomy_terms_keyed_by_id[$taxonomy][$taxonomyTerm->term_id];
        }

        self::markTaxonomyAsLoaded($taxonomy);
    }

    private static function markTaxonomyAsLoaded(string $taxonomy): void
    {
        self::$loaded[$taxonomy] = true;
    }

    /**
     * @param string $taxonomy
     * @return WP_Term[]
     * @throws EmptyQueryBuilderArguments
     */
    private static function searchTaxonomyTerms(string $taxonomy): array
    {
        return TermQueryBuilder::make($taxonomy)->get();
    }
}
