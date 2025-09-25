<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy;

use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Infrastructure\Wordpress\Taxonomy\Dictionary\Traits\Initializer;
use WP_Term;

abstract class Dictionary extends Singleton
{
    use Initializer;

    /**
     * @var array<string, array<int, WP_Term>>
     */
    private static array $taxonomy_terms_keyed_by_id;
    /**
     * @var array<string, array<string, WP_Term>>
     */
    private static array $taxonomy_terms_keyed_by_name;
    /**
     * @var array<string, array<string, WP_Term>>
     */
    private static array $taxonomy_terms_keyed_by_slug;

    public static function getInstance(): static
    {
        return parent::getInstance();
    }

    protected function __construct(readonly private string $taxonomy)
    {
        parent::__construct();

        self::init($this->taxonomy);
    }

    private static function init(string $taxonomy): void
    {
        self::initializeLoaded();

        if (self::isTaxonomyLoaded($taxonomy)) {
            return;
        }

        self::initializeInternalDictionaries();
        self::loadTaxonomyInitializedInternalDictionaries($taxonomy);
    }

    /**
     * @return WP_Term[]
     */
    public function all(): array
    {
        return self::$taxonomy_terms_keyed_by_id[$this->taxonomy] ?? [];
    }

    public function get(int|string $category): ?WP_Term
    {
        if (is_int($category) || is_numeric($category)) {
            return $this->getById((int)$category);
        }

        return $this->getBySlug($category) ?? $this->getByName($category);
    }

    public function getById(int $id): ?WP_Term
    {
        return self::$taxonomy_terms_keyed_by_id[$this->taxonomy][$id] ?? null;
    }

    public function getByName(string $name): ?WP_Term
    {
        return self::$taxonomy_terms_keyed_by_name[$this->taxonomy][esc_html($name)] ?? null;
    }

    public function getBySlug(string $slug): ?WP_Term
    {
        return self::$taxonomy_terms_keyed_by_slug[$this->taxonomy][$slug] ?? null;
    }

    public function reload(): static
    {
        unset(self::$taxonomy_terms_keyed_by_id[$this->taxonomy]);
        unset(self::$taxonomy_terms_keyed_by_name[$this->taxonomy]);
        unset(self::$taxonomy_terms_keyed_by_slug[$this->taxonomy]);

        self::loadTaxonomyInitializedInternalDictionaries($this->taxonomy);

        return $this;
    }

    public function unsetById(int $term_id): static
    {
        if (!is_null($term = static::getById($term_id))) {
            unset(self::$taxonomy_terms_keyed_by_id[$this->taxonomy][$term_id]);
            unset(self::$taxonomy_terms_keyed_by_name[$this->taxonomy][$term->name]);
            unset(self::$taxonomy_terms_keyed_by_slug[$this->taxonomy][$term->slug]);
        }

        return $this;
    }
}
