<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedComments\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedComments;

class FailedToConstructPaginatedComments extends RuntimeException
{
    public function __construct(
        public readonly CommentQueryBuilder $queryBuilder,
        public readonly int              $items_per_page,
        ?Throwable                       $previous = null
    )
    {
        parent::__construct('Could not instantiate a '
            . PaginatedComments::class
            . " object with $this->items_per_page items per page and the given query.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
