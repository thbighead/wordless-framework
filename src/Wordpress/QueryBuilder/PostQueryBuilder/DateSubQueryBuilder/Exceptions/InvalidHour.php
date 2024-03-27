<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidHour extends DomainException
{
    public function __construct(public readonly int $hour, ?Throwable $previous = null)
    {
        parent::__construct(
            "Hour must be a integer and between 0 and 23, provided $this->hour.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
