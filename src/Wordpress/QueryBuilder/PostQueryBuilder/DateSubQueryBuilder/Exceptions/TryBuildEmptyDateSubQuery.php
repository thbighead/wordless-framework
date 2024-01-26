<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class TryBuildEmptyDateSubQuery extends ErrorException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            "Failed on try build empty DateSubQueryBuilder.",
            previous: $previous
        );
    }
}
