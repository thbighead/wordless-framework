<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class InvalidDayOfYear extends ErrorException
{
    public function __construct(public readonly int $day, ?Throwable $previous = null)
    {
        parent::__construct(
            "Wordpress day of year must be between 1 and 366, provided $this->day.",
            previous: $previous
        );
    }
}
