<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedUsers;

use Wordless\Application\Libraries\Pagination\Pages\Traits\Rotate;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedUsers;

class Rotating extends PaginatedUsers
{
    use Rotate;
}
