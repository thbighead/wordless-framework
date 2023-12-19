<?php

namespace Wordless\Wordpress\Models\Category;

use Wordless\Application\Libraries\DesignPattern\Singleton;
use WP_Term;

class Dictionary extends Singleton
{
    /**
     * @var WP_Term[]
     */
    private static array $categories_keyed_by_id = [];
    /**
     * @var WP_Term[]
     */
    private static array $categories_keyed_by_name = [];
    /**
     * @var WP_Term[]
     */
    private static array $categories_keyed_by_slug = [];
    private static bool $loaded = false;

    protected function __construct()
    {
        parent::__construct();

        self::init();
    }

    private static function init(): void
    {
        if (self::$loaded) {
            return;
        }

        foreach (get_categories() as $category) {
            /** @var WP_Term $category */
            self::$categories_keyed_by_id[$category->term_id] = $category;
            self::$categories_keyed_by_name[$category->name] = self::$categories_keyed_by_slug[$category->slug] =
            &self::$categories_keyed_by_id[$category->term_id];
        }

        self::$loaded = true;
    }

    /**
     * @return WP_Term[]
     */
    public function all(): array
    {
        return self::$categories_keyed_by_id;
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
        return self::$categories_keyed_by_id[$id] ?? null;
    }

    public function getByName(string $name): ?WP_Term
    {
        return self::$categories_keyed_by_name[$name] ?? null;
    }

    public function getBySlug(string $slug): ?WP_Term
    {
        return self::$categories_keyed_by_slug[$slug] ?? null;
    }
}
