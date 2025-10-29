<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits;

use Wordless\Application\Libraries\Pagination\Pages\Page\Exceptions\EmptyPage;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedPosts;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedPosts\Rotating;

trait Pagination
{
    final public const KEY_NO_FOUND_ROWS = 'no_found_rows';
    final public const KEY_NO_PAGING = 'nopaging';
    private const KEY_POSTS_PER_PAGE = 'posts_per_page';
    private const KEY_PAGED = 'paged';

    private bool $is_paginating = false;

    public function isPaginating(): bool
    {
        return $this->is_paginating;
    }

    /**
     * @param int $posts_per_page
     * @param array $extra_arguments
     * @return PaginatedPosts
     * @throws EmptyPage
     * @throws EmptyQueryBuilderArguments
     */
    public function paginate(int $posts_per_page, array $extra_arguments = []): PaginatedPosts
    {
        return new PaginatedPosts(
            $this->resolveExtraArguments($this->arguments, $extra_arguments),
            max($posts_per_page, 1)
        );
    }

    /**
     * @param int $posts_per_page
     * @param array $extra_arguments
     * @return Rotating
     * @throws EmptyPage
     * @throws EmptyQueryBuilderArguments
     */
    public function paginateRotating(int $posts_per_page, array $extra_arguments = []): Rotating
    {
        return new Rotating(
            $this->resolveExtraArguments($this->arguments, $extra_arguments),
            max($posts_per_page, 1)
        );
    }

    public function preparePagination(int $posts_per_page, int $page = 1): static
    {
        $this->arguments[self::KEY_NO_FOUND_ROWS] = false;
        $this->arguments[self::KEY_NO_PAGING] = false;
        $this->arguments[self::KEY_POSTS_PER_PAGE] = max($posts_per_page, 1);
        $this->arguments[self::KEY_PAGED] = $page;

        $this->is_paginating = true;

        return $this;
    }

    private function deactivatePagination(): static
    {
        $this->arguments[self::KEY_NO_FOUND_ROWS] = true;
        $this->arguments[self::KEY_NO_PAGING] = true;
        $this->arguments[self::KEY_POSTS_PER_PAGE] = -1;

        $this->is_paginating = false;

        return $this;
    }
}
