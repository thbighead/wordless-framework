<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class InvalidYearMonth extends ErrorException
{
    public function __construct(public readonly int $year, public readonly int $month, ?Throwable $previous = null)
    {
        parent::__construct(
            "Invalid year-month, year must be four digits integer, $this->year provided, and month must be
            between 1 and 12, $this->month provided.",
            previous: $previous
        );
    }
}
