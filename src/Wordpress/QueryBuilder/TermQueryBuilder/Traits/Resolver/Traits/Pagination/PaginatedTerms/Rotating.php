<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedTerms;

use Wordless\Application\Libraries\Pagination\Pages\Traits\Rotate;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedTerms;

class Rotating extends PaginatedTerms
{
    use Rotate;
}
