<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class TryingToSetEmptyPostType extends ErrorException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Empty arrays for post_type parameter is forbidden.',
            previous: $previous
        );
    }
}
