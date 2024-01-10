<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\QueryBuilder\Enums\OrderByDirection;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums\ColumnParameter;

class InvalidOrderByClause extends DomainException
{
    public function __construct(
        public readonly mixed $column,
        public readonly mixed $direction,
        ?Throwable            $previous = null
    )
    {
        parent::__construct(
            'Order by associative array clauses must have a '
            . OrderByDirection::class
            . ' object value keyed by '
            . ColumnParameter::class
            . ' string value. ',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
