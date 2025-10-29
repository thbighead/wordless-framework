<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Traits\Pagination;

use Wordless\Application\Libraries\Pagination\Pages;
use Wordless\Application\Libraries\Pagination\Pages\Page\Exceptions\EmptyPage;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Exceptions\TryingToOrderByMetaWithoutMetaQuery;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedComments\Exceptions\FailedToConstructPaginatedComments;
use WP_Comment;

class PaginatedComments extends Pages
{
    /**
     * @param CommentQueryBuilder $queryBuilder
     * @param int $items_per_page
     * @throws FailedToConstructPaginatedComments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public function __construct(
        private readonly CommentQueryBuilder $queryBuilder,
        int                                  $items_per_page
    )
    {
        try {
            parent::__construct($items_per_page, $this->queryBuilder->count());

            $this->queryBuilder->limit($this->items_per_page);
        } catch (EmptyQueryBuilderArguments|EmptyPage $exception) {
            throw new FailedToConstructPaginatedComments($queryBuilder, $items_per_page, $exception);
        }
    }

    /**
     * @param int $valid_index
     * @return WP_Comment[]
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    protected function getPageItems(int $valid_index): array
    {
        return $this->queryBuilder->offset($valid_index * $this->items_per_page)->get();
    }
}
