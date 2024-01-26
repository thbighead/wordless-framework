<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidMinute extends DomainException
{
    public function __construct(public readonly int $minute, ?Throwable $previous = null)
    {
        parent::__construct(
            "Minute must be a integer and between 0 and 60, provided $this->minute.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
