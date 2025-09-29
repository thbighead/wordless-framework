<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver\Traits\Pagination;

use Wordless\Application\Libraries\Pagination\Pages;
use Wordless\Application\Libraries\Pagination\Pages\Page\Exceptions\EmptyPage;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Exceptions\DoNotUseNumberWithObjectIds;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedTerms\Exceptions\FailedToConstructPaginatedTerms;
use WP_Term;

class PaginatedTerms extends Pages
{
    /**
     * @param TermQueryBuilder $queryBuilder
     * @param int $items_per_page
     * @throws FailedToConstructPaginatedTerms
     */
    public function __construct(
        private readonly TermQueryBuilder $queryBuilder,
        int                               $items_per_page
    )
    {
        try {
            parent::__construct($items_per_page, $this->queryBuilder->count());

            $this->queryBuilder->limit($this->items_per_page);
        } catch (DoNotUseNumberWithObjectIds|EmptyQueryBuilderArguments|EmptyPage $exception) {
            throw new FailedToConstructPaginatedTerms($queryBuilder, $items_per_page, $exception);
        }
    }

    /**
     * @param int $valid_index
     * @return WP_Term[]
     * @throws EmptyQueryBuilderArguments
     */
    protected function getPageItems(int $valid_index): array
    {
        return $this->queryBuilder->offset($valid_index * $this->items_per_page)->get();
    }
}
