<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedTerms\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedTerms;

class FailedToConstructPaginatedTerms extends RuntimeException
{
    public function __construct(
        public readonly TermQueryBuilder $queryBuilder,
        public readonly int              $items_per_page,
        ?Throwable                       $previous = null
    )
    {
        parent::__construct('Could not instantiate a '
            . PaginatedTerms::class
            . " object with $this->items_per_page items per page and the given query.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
