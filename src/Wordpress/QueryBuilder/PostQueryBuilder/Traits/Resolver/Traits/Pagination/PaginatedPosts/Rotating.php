<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedPosts;

use Wordless\Application\Libraries\Pagination\Pages\Traits\Rotate;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedPosts;

class Rotating extends PaginatedPosts
{
    use Rotate;
}
