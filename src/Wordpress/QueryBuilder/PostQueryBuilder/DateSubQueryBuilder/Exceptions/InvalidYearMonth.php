<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidYearMonth extends DomainException
{
    public function __construct(public readonly int $year, public readonly int $month, ?Throwable $previous = null)
    {
        parent::__construct(
            "Invalid year-month, year must be four digits integer, $this->year provided, and month must be
            between 1 and 12, $this->month provided.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
