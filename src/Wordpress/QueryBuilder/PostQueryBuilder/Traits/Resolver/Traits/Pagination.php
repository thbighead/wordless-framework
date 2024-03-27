<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits;

use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Pagination\Posts;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PaginationArgumentsBuilder;

trait Pagination
{
    final public const KEY_NO_FOUND_ROWS = 'no_found_rows';
    final public const KEY_NO_PAGING = 'nopaging';

    public function getNumberOfPages(): ?int
    {
        if (!$this->arePostsAlreadyLoaded()) {
            $this->query();
        }

        return $this->getQuery()->max_num_pages;
    }

    /**
     * @param PaginationArgumentsBuilder $paginationBuilder
     * @return Posts
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function paginate(PaginationArgumentsBuilder $paginationBuilder): Posts
    {
        return new Posts($this, $paginationBuilder);
    }

    private function deactivatePagination(): static
    {
        $this->arguments[self::KEY_NO_FOUND_ROWS] = true;
        $this->arguments[self::KEY_NO_PAGING] = true;
        $this->arguments[Posts::KEY_POSTS_PER_PAGE] = -1;

        return $this;
    }
}
