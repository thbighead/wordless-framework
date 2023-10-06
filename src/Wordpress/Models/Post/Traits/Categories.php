<?php

namespace Wordless\Wordpress\Models\Post\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\Models\Category;

trait Categories
{
    /** @var Category[] $categories */
    private array $categories;

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        if (isset($this->categories)) {
            return $this->categories;
        }

        $this->categories = [];

        foreach ($this->getCategoriesIds() as $category_id) {
            $this->categories[] = Category::getById($category_id);
        }

        return $this->categories;
    }

    public function getCategory(): ?Category
    {
        return $this->getCategories()[0] ?? null;
    }

    /**
     * @return int[]
     */
    private function getCategoriesIds(): array
    {
        return Arr::wrap($this->wpPost->post_category);
    }
}
