<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Traits;

use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Exceptions\TryingToOrderByMetaWithoutMetaQuery;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedComments;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedComments\Exceptions\FailedToConstructPaginatedComments;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedComments\Rotating;

trait Pagination
{
    private const KEY_NO_FOUND_ROWS = 'no_found_rows';

    public function offset(int $offset): static
    {
        if (($offset = max($offset, 0)) > 0) {
            $this->arguments['offset'] = $offset;
        }

        return $this;
    }

    /**
     * @param int $comments_per_page
     * @param bool $only_ids
     * @param array $extra_arguments
     * @return PaginatedComments
     * @throws FailedToConstructPaginatedComments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public function paginate(
        int   $comments_per_page,
        bool  $only_ids = false,
        array $extra_arguments = []
    ): PaginatedComments
    {
        return new PaginatedComments(
            $this->preparePagination($only_ids)
                ->resolveExtraArguments($this->arguments, $extra_arguments),
            max($comments_per_page, 1)
        );
    }

    /**
     * @param int $comments_per_page
     * @param bool $only_ids
     * @param array $extra_arguments
     * @return Rotating
     * @throws FailedToConstructPaginatedComments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public function paginateRotating(
        int   $comments_per_page,
        bool  $only_ids = false,
        array $extra_arguments = []
    ): Rotating
    {
        return new Rotating(
            $this->preparePagination($only_ids)
                ->resolveExtraArguments($this->arguments, $extra_arguments),
            max($comments_per_page, 1)
        );
    }

    private function preparePagination(bool $only_ids): static
    {
        $this->arguments[self::KEY_NO_FOUND_ROWS] = false;

        if ($only_ids) {
            $this->setToReturnOnlyIds();
        }

        return $this;
    }
}
