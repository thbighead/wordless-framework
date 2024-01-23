<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class InvalidYear extends ErrorException
{
    public function __construct(public readonly int $year, ?Throwable $previous = null)
    {
        parent::__construct(
            "Wordpress year parameter must be four digits integer, provided $this->year.",
            previous: $previous
        );
    }
}
