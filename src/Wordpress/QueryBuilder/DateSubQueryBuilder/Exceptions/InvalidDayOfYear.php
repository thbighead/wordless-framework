<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidDayOfYear extends DomainException
{
    public function __construct(public readonly int $day, ?Throwable $previous = null)
    {
        parent::__construct(
            "Wordpress day of year must be between 1 and 366, provided $this->day.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
