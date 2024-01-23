<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class InvalidMonth extends ErrorException
{
    public function __construct(public readonly int $month, ?Throwable $previous = null)
    {
        parent::__construct(
            "Month must be between 1 and 12, provided $this->month.",
            previous: $previous
        );
    }
}
