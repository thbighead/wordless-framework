<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PaginationArgumentsBuilder\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidPostsPerPage extends DomainException
{
     public function __construct(public readonly int $invalid_posts_per_page, ?Throwable $previous = null)
     {
         parent::__construct(
             "The number of posts per page must be greater than 0 when paginating, $this->invalid_posts_per_page given.",
             ExceptionCode::development_error->value,
             $previous
         );
     }
}
