<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class InvalidWeekDay extends ErrorException
{
    public function __construct(public readonly int $week, ?Throwable $previous = null)
    {
        parent::__construct(
            "Week day must be an integer and between 1 and 7, $this->week provided.",
            previous: $previous
        );
    }
}
