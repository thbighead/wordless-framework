<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class TrySetEmptyPostType extends ErrorException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed on try set empty array for post_type parameter on method "whereType()".',
            previous: $previous
        );
    }
}
