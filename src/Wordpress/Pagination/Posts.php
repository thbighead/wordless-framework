<?php declare(strict_types=1);

namespace Wordless\Wordpress\Pagination;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PaginationArgumentsBuilder;

final class Posts
{
    public const KEY_POSTS_PER_PAGE = 'posts_per_page';
    public const KEY_PAGED = 'paged';
    public const DEFAULT_POSTS_PER_PAGE = 10;
    public const FIRST_PAGE = 1;

    private Page $currentPage;
    public readonly int $number_of_pages;
    public readonly int $total_number_of_posts;

    /**
     * @param PostQueryBuilder $queryBuilder
     * @param PaginationArgumentsBuilder $paginationBuilder
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     * @throws EmptyQueryBuilderArguments
     */
    public function __construct(
        private readonly PostQueryBuilder           $queryBuilder,
        private readonly PaginationArgumentsBuilder $paginationBuilder
    )
    {
        $posts = $this->query();

        $this->total_number_of_posts = $this->queryBuilder->count();
        $this->number_of_pages = $this->queryBuilder->getNumberOfPages();
        $this->currentPage = new Page($this->paginationBuilder->getPaged(), $posts);
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

    /**
     * @param int $page
     * @return $this
     * @throws EmptyQueryBuilderArguments
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function goToPage(int $page): Posts
    {
        if (($page = $this->calculatePageInsideRange($page)) !== $this->getCurrentPageNumber()) {
            $this->paginationBuilder->setPaged($page);
            $this->currentPage = new Page($page, $this->query());
        }

        return $this;
    }

    /**
     * @return $this
     * @throws EmptyQueryBuilderArguments
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function nextPage(): Posts
    {
        return $this->goToPage($this->getCurrentPageNumber() + 1);
    }

    /**
     * @param int $page
     * @return Post[]
     * @throws EmptyQueryBuilderArguments
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function retrievePageItems(int $page): array
    {
        return $this->goToPage($page)->getCurrentPageItems();
    }

    /**
     * @return Post[]
     * @throws EmptyQueryBuilderArguments
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function retrieveNextPageItems(): array
    {
        return $this->nextPage()->getCurrentPageItems();
    }

    /**
     * @param int $page
     * @return int
     */
    private function calculatePageInsideRange(int $page): int
    {
        return min(max($page, self::FIRST_PAGE), $this->number_of_pages);
    }

    /**
     * @return Post[]
     * @throws EmptyQueryBuilderArguments
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    private function query(): array
    {
        return $this->queryBuilder->get(
            $this->paginationBuilder->load_acfs,
            $this->paginationBuilder->getPaginationArguments()
        );
    }
}
