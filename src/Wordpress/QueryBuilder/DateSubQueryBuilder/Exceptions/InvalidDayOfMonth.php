<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidDayOfMonth extends DomainException
{
    public function __construct(public readonly int $day, ?Throwable $previous = null)
    {
        parent::__construct(
            "Day must be between 1 and 31, $this->day provided.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
