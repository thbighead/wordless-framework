<?php

namespace Wordless\Adapters;

use Wordless\Abstractions\CategoriesList;
use Wordless\Contracts\Adapter\RelatedMetaData;
use Wordless\Contracts\Adapter\WithAcfs;
use Wordless\Contracts\Adapter\WithMetaData;
use WP_Term;

class Category implements RelatedMetaData
{
    use WithAcfs, WithMetaData;

    private static CategoriesList $categories;

    private array $acfs = [];
    private WP_Term $wpCategory;

    /**
     * @return WP_Term[]
     */
    public static function all(): array
    {
        return self::getCategoriesList()->all();
    }

    /**
     * @param string|int $category
     * @return WP_Term|null
     */
    public static function find($category): ?WP_Term
    {
        if (is_int($category) || is_numeric($category)) {
            return static::getById((int)$category);
        }

        return static::getBySlug($category) ?? static::getByName($category);
    }

    public static function getById(int $id): ?WP_Term
    {
        return self::getCategoriesList()->getById($id);
    }

    public static function getByName(string $name): ?WP_Term
    {
        return self::getCategoriesList()->getByName($name);
    }

    public static function getBySlug(string $slug): ?WP_Term
    {
        return self::getCategoriesList()->getBySlug($slug);
    }

    private static function getCategoriesList(): CategoriesList
    {
        return self::$categories ?? self::$categories = new CategoriesList;
    }

    public static function objectType(): string
    {
        return 'term';
    }

    /**
     * @param WP_Term|int|string $category
     * @param bool $with_acfs
     */
    public function __construct($category, bool $with_acfs = true)
    {
        $this->wpCategory = $category instanceof WP_Term ? $category : static::find($category);

        if ($with_acfs) {
            $this->loadCategoryAcfs($this->wpCategory->term_id);
        }
    }

    public function asWpTerm(): ?WP_Term
    {
        return $this->wpCategory;
    }

    private function loadCategoryAcfs(int $from_id)
    {
        $this->loadAcfs("category_$from_id");
    }
}
