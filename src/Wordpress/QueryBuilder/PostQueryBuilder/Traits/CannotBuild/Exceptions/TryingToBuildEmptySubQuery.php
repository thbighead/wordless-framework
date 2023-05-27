<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\CannotBuild\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Enums\ExceptionCode;

class TryingToBuildEmptySubQuery extends ErrorException
{
    public function __construct(private readonly string $subQueryClass, ?Throwable $previous = null)
    {
        parent::__construct(
            "$subQueryClass can't be built.",
            ExceptionCode::development_error->value,
            previous: $previous
        );
    }

    public function getSubQueryClass(): string
    {
        return $this->subQueryClass;
    }
}
