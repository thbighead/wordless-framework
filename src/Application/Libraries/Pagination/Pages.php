<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Pagination;

use Wordless\Application\Libraries\Pagination\Pages\Page;

abstract class Pages
{
    abstract protected function getPageItems(int $index): array;

    public readonly int $items_per_page;
    public readonly int $number_of_pages;
    private Page $currentPage;
    /** @var array<int, Page> $pages_collection */
    private array $pages_collection = [];

    public function __construct(
        int $items_per_page,
        public readonly int $items_total,
        public readonly int $initial_page_index = 0
    )
    {
        $this->updateCurrentPage($this->initial_page_index);
        $this->items_per_page = max($items_per_page, 1);
        $this->number_of_pages = (int)ceil($this->items_total/$this->items_per_page);
    }

    public function currentPage(): Page
    {
        return $this->currentPage;
    }

    public function goToPage(int $index): ?Page
    {
        if ($index < 0 || $index >= $this->number_of_pages) {
            return null;
        }

        return $this->updateCurrentPage($index);
    }

    public function nextPage(): ?Page
    {
        if (!is_null($nextPage = $this->goToPage($this->currentPage->index + 1))) {
            $this->currentPage = $nextPage;
        }

        return $nextPage;
    }

    public function previousPage(): ?Page
    {
        if (!is_null($previousPage = $this->goToPage($this->currentPage->index - 1))) {
            $this->currentPage = $previousPage;
        }

        return $previousPage;
    }

    protected function mountPage(int $index): Page
    {
        return new Page($index, $this->getPageItems($index));
    }

    private function updateCurrentPage(int $index): Page
    {
        if (isset($this->pages_collection[$index])) {
            return $this->currentPage = $this->pages_collection[$index];
        }

        return $this->currentPage = $this->pages_collection[$index] = $this->mountPage($index);
    }
}
