<?php

namespace Wordless\Wordpress\Models;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;
use Wordless\Wordpress\Models\Category\Dictionary;
use WP_Term;

class Category extends Taxonomy
{
    final protected const NAME_KEY = StandardTaxonomy::category->name;

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

    protected static function getCategoriesList(): Dictionary
    {
        return Dictionary::getInstance();
    }

    final protected function setWpTaxonomy(): void
    {
        $this->wpTaxonomy = get_taxonomy(self::NAME_KEY);
    }
}
