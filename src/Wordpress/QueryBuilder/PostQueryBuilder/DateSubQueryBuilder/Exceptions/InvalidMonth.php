<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidMonth extends DomainException
{
    public function __construct(public readonly int $month, ?Throwable $previous = null)
    {
        parent::__construct(
            "Month must be between 1 and 12, provided $this->month.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
