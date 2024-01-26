<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidDayOfWeek extends DomainException
{
    public function __construct(public readonly int $week, ?Throwable $previous = null)
    {
        parent::__construct(
            "Week day must be an integer and between 1 and 7, $this->week provided.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
