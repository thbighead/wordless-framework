<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class InvalidDayOfMonth extends ErrorException
{
    public function __construct(public readonly int $day, ?Throwable $previous = null)
    {
        parent::__construct(
            "Day must be between 1 and 31, $this->day provided.",
            previous: $previous
        );
    }
}
