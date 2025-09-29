<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\Pagination;

use Wordless\Application\Libraries\Pagination\Pages;
use Wordless\Application\Libraries\Pagination\Pages\Page\Exceptions\EmptyPage;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use WP_Post;

class PaginatedPosts extends Pages
{
    private array $initial_page_result;

    /**
     * @param PostQueryBuilder $queryBuilder
     * @param int $items_per_page
     * @throws EmptyPage
     * @throws EmptyQueryBuilderArguments
     */
    public function __construct(
        private readonly PostQueryBuilder $queryBuilder,
        int                               $items_per_page
    )
    {
        if (!$this->queryBuilder->isPaginating()) {
            $this->queryBuilder->preparePagination($items_per_page);
        }

        $this->initial_page_result = $this->queryBuilder->get();

        parent::__construct($items_per_page, $this->queryBuilder->count());
    }

    /**
     * @param int $valid_index
     * @return WP_Post[]
     * @throws EmptyQueryBuilderArguments
     */
    protected function getPageItems(int $valid_index): array
    {
        if (isset($this->initial_page_result)) {
            $items = $this->initial_page_result;

            unset($this->initial_page_result);

            return $items;
        }

        return $this->queryBuilder->preparePagination($valid_index + 1)->get();
    }
}
