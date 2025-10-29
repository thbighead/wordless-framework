<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Pagination;

use Wordless\Application\Libraries\Pagination\Pages\Page;
use Wordless\Application\Libraries\Pagination\Pages\Page\Exceptions\EmptyPage;

abstract class Pages
{
    abstract protected function getPageItems(int $valid_index): array;

    public readonly int $initial_page_index;
    public readonly int $items_per_page;
    public readonly int $number_of_pages;
    private Page $currentPage;
    /** @var array<int, Page> $pages_collection */
    private array $pages_collection = [];

    /**
     * @param int $items_per_page
     * @param int $items_total
     * @param int $initial_page_index
     * @throws EmptyPage
     */
    public function __construct(
        int                 $items_per_page,
        public readonly int $items_total,
        int                 $initial_page_index = 0
    )
    {
        $this->items_per_page = max($items_per_page, 1);
        $this->number_of_pages = (int)ceil($this->items_total / $this->items_per_page);
        $this->updateCurrentPage(
            $this->initial_page_index = $this->calculateValidInitialPageIndex($initial_page_index)
        );
    }

    public function currentPage(): Page
    {
        return $this->currentPage;
    }

    /**
     * @param int $index
     * @return Page|null
     * @throws EmptyPage
     */
    public function goToPage(int $index): ?Page
    {
        if ($index < 0 || $index >= $this->number_of_pages) {
            return null;
        }

        return $this->updateCurrentPage($index);
    }

    /**
     * @return Page|null
     * @throws EmptyPage
     */
    public function nextPage(): ?Page
    {
        if (!is_null($nextPage = $this->goToPage($this->currentPage->index + 1))) {
            $this->currentPage = $nextPage;
        }

        return $nextPage;
    }

    /**
     * @return Page|null
     * @throws EmptyPage
     */
    public function previousPage(): ?Page
    {
        if (!is_null($previousPage = $this->goToPage($this->currentPage->index - 1))) {
            $this->currentPage = $previousPage;
        }

        return $previousPage;
    }

    protected function calculateValidInitialPageIndex(int $initial_page_index): int
    {
        return min(max($initial_page_index, 0), $this->number_of_pages - 1);
    }

    /**
     * @param int $valid_index
     * @return Page
     * @throws EmptyPage
     */
    protected function mountPage(int $valid_index): Page
    {
        return new Page($valid_index, $this->getPageItems($valid_index));
    }

    /**
     * @param int $valid_index
     * @return Page
     * @throws EmptyPage
     */
    private function updateCurrentPage(int $valid_index): Page
    {
        if (isset($this->pages_collection[$valid_index])) {
            return $this->currentPage = $this->pages_collection[$valid_index];
        }

        return $this->currentPage = $this->pages_collection[$valid_index] = $this->mountPage($valid_index);
    }
}
