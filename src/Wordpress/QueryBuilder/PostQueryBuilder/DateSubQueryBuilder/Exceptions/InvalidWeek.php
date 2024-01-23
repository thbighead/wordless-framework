<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class InvalidWeek extends ErrorException
{
    public function __construct(public readonly int $week, ?Throwable $previous = null)
    {
        parent::__construct(
            "Week must be an integer and between 0 and 53, $this->week provided.",
            previous: $previous
        );
    }
}
