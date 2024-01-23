<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class InvalidMinute extends ErrorException
{
    public function __construct(public readonly int $minute, ?Throwable $previous = null)
    {
        parent::__construct(
            "Minute must be a integer and between 0 and 60, provided $this->minute.",
            previous: $previous
        );
    }
}
