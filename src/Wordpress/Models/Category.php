<?php

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\CategoriesList;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Enums\MetableObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Traits\WithAcfs;
use WP_Term;

class Category implements IRelatedMetaData
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

    public static function find(int|string $category): ?WP_Term
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

    public static function objectType(): MetableObjectType
    {
        return MetableObjectType::term;
    }

    private static function getCategoriesList(): CategoriesList
    {
        return self::$categories ?? self::$categories = new CategoriesList;
    }

    public function __construct(WP_Term|int|string $category, bool $with_acfs = true)
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

    private function loadCategoryAcfs(int $from_id): void
    {
        $this->loadAcfs("category_$from_id");
    }
}
