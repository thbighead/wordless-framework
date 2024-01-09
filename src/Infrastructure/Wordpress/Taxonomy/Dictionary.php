<?php

namespace Wordless\Infrastructure\Wordpress\Taxonomy;

use Wordless\Application\Libraries\DesignPattern\Singleton;
use WP_Term;

abstract class Dictionary extends Singleton
{
    /**
     * @var array<int, WP_Term>
     */
    private static array $taxonomy_terms_keyed_by_id;
    /**
     * @var array<string, WP_Term>
     */
    private static array $taxonomy_terms_keyed_by_name;
    /**
     * @var array<string, WP_Term>
     */
    private static array $taxonomy_terms_keyed_by_slug;
    private static bool $loaded = false;

    public function __construct(string $taxonomy)
    {
        parent::__construct();

        self::init($taxonomy);
    }

    private static function init(string $taxonomy): void
    {
        if (self::$loaded) {
            return;
        }

        self::$taxonomy_terms_keyed_by_id = [];
        self::$taxonomy_terms_keyed_by_name = [];
        self::$taxonomy_terms_keyed_by_slug = [];

        foreach (get_terms(['hide_empty' => false,'taxonomy' => $taxonomy]) as $taxonomyTerm) {
            /** @var WP_Term $taxonomyTerm */
            self::$taxonomy_terms_keyed_by_id[$taxonomyTerm->term_id] = $taxonomyTerm;
            self::$taxonomy_terms_keyed_by_name[$taxonomyTerm->name] =
            self::$taxonomy_terms_keyed_by_slug[$taxonomyTerm->slug] =
            &self::$taxonomy_terms_keyed_by_id[$taxonomyTerm->term_id];
        }

        self::$loaded = true;
    }

    /**
     * @return WP_Term[]
     */
    public function all(): array
    {
        return self::$taxonomy_terms_keyed_by_id;
    }

    public function find(int|string $category): ?WP_Term
    {
        if (is_int($category) || is_numeric($category)) {
            return $this->getById((int)$category);
        }

        return $this->getBySlug($category) ?? $this->getByName($category);
    }

    public function getById(int $id): ?WP_Term
    {
        return self::$taxonomy_terms_keyed_by_id[$id] ?? null;
    }

    public function getByName(string $name): ?WP_Term
    {
        return self::$taxonomy_terms_keyed_by_name[$name] ?? null;
    }

    public function getBySlug(string $slug): ?WP_Term
    {
        return self::$taxonomy_terms_keyed_by_slug[$slug] ?? null;
    }
}
