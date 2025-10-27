<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedComments;

use Wordless\Application\Libraries\Pagination\Pages\Traits\Rotate;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedComments;

class Rotating extends PaginatedComments
{
    use Rotate;
}
