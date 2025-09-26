<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\Pagination;

use Wordless\Application\Libraries\Pagination\Pages;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder;
use WP_User;

class PaginatedUsers extends Pages
{
    private array $initial_page_result;

    /**
     * @param UserQueryBuilder $queryBuilder
     * @param int $items_per_page
     * @param int $initial_page_index
     * @throws EmptyQueryBuilderArguments
     */
    public function __construct(
        private readonly UserQueryBuilder $queryBuilder,
        int $items_per_page,
        int $initial_page_index = 0
    )
    {
        $this->initial_page_result = $this->queryBuilder->get();

        parent::__construct($items_per_page, $this->queryBuilder->count(), $initial_page_index);
    }

    /**
     * @param int $index
     * @return WP_User[]
     * @throws EmptyQueryBuilderArguments
     */
    protected function getPageItems(int $index): array
    {
        if ($this->initial_page_result) {
            $items = $this->initial_page_result;

            unset($this->initial_page_result);

            return $items;
        }

        return $this->queryBuilder->preparePagination($index + 1)->get();
    }
}
