<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Wordpress\Pagination\Posts;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PaginationArgumentsBuilder\Exceptions\InvalidPostsPerPage;

class PaginationArgumentsBuilder
{
    private array $pagination_arguments = [
        PostQueryBuilder::KEY_NO_FOUND_ROWS => false,
        PostQueryBuilder::KEY_NO_PAGING => false,
        Posts::KEY_POSTS_PER_PAGE => Posts::DEFAULT_POSTS_PER_PAGE,
        Posts::KEY_PAGED => Posts::FIRST_PAGE,
    ];

    public function __construct(public readonly bool $load_acfs = false)
    {
    }

    public function getPaged(): int
    {
        return $this->pagination_arguments[Posts::KEY_PAGED];
    }

    /**
     * @return array<string, mixed>
     */
    public function getPaginationArguments(): array
    {
        return $this->pagination_arguments;
    }

    public function setPaged(int $page): PaginationArgumentsBuilder
    {
        $this->pagination_arguments[Posts::KEY_PAGED] = (int)max(abs($page), Posts::FIRST_PAGE);

        return $this;
    }

    /**
     * @param int $posts_per_page
     * @return $this
     * @throws InvalidPostsPerPage
     */
    public function setPostsPerPage(int $posts_per_page): PaginationArgumentsBuilder
    {
        $this->pagination_arguments[Posts::KEY_POSTS_PER_PAGE] = $this->validatePostsPerPage($posts_per_page);

        return $this;
    }

    /**
     * @param int $posts_per_page
     * @return int
     * @throws InvalidPostsPerPage
     */
    private function validatePostsPerPage(int $posts_per_page): int
    {
        if ($posts_per_page <= 0) {
            throw new InvalidPostsPerPage($posts_per_page);
        }

        return $posts_per_page;
    }
}
