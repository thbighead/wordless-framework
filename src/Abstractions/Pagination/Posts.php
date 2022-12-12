<?php

namespace Wordless\Abstractions\Pagination;

use Wordless\Adapters\Post;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder;

class Posts
{
    public const KEY_POSTS_PER_PAGE = 'posts_per_page';
    public const KEY_PAGED = 'paged';
    public const DEFAULT_POSTS_PER_PAGE = 10;
    public const FIRST_PAGE = 1;

    private Page $currentPage;
    private int $number_of_pages;
    private int $total_number_of_posts;

    private PostQueryBuilder $queryBuilder;

    public function __construct(PostQueryBuilder $queryBuilder, int $begin_at_page = self::FIRST_PAGE)
    {
        $this->queryBuilder = $queryBuilder;

        if (!$this->queryBuilder->isPaginated()) {
            $this->queryBuilder->setPostsPerPage();
        }

        $posts = $this->queryBuilder->get($this->queryBuilder->shouldLoadAcfs());

        $this->total_number_of_posts = $this->queryBuilder->count();
        $this->number_of_pages = $this->queryBuilder->getNumberOfPages();

        $this->currentPage = new Page($begin_at_page, $posts);
    }

    /**
     * @return Post[]
     */
    public function getCurrentPageItems(): array
    {
        return $this->currentPage->getItems();
    }

    public function getCurrentPageNumber(): int
    {
        return $this->currentPage->getNumber();
    }

    public function getNumberOfPages(): int
    {
        return $this->number_of_pages;
    }

    public function getTotalNumberOfPosts(): int
    {
        return $this->total_number_of_posts;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function goToPage(int $page): Posts
    {
        if (($page = max($page, self::FIRST_PAGE)) === $this->getCurrentPageNumber()) {
            return $this;
        }

        $this->currentPage = new Page($page, $this->queryBuilder->pagedAt($page)->get());

        return $this;
    }

    /**
     * @return $this
     */
    public function nextPage(): Posts
    {
        return $this->goToPage($this->getCurrentPageNumber() + 1);
    }

    /**
     * @param int $page
     * @return Post[]
     */
    public function retrievePageItems(int $page): array
    {
        return $this->goToPage($page)->getCurrentPageItems();
    }

    /**
     * @return Post[]
     */
    public function retrieveNextPageItems(): array
    {
        return $this->nextPage()->getCurrentPageItems();
    }
}
