<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class InvalidSecond extends ErrorException
{
    public function __construct(public readonly int $second, ?Throwable $previous = null)
    {
        parent::__construct(
            "Second must be a integer and between 0 and 60, provided $this->second.",
            previous: $previous
        );
    }
}
