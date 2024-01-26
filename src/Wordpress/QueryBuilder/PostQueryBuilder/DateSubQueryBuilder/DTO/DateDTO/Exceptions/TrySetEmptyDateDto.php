<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDto\Exceptions;

use ErrorException;
use Throwable;

class TrySetEmptyDateDto extends ErrorException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed on try construct empty DateDTO.',
            previous: $previous
        );
    }
}
