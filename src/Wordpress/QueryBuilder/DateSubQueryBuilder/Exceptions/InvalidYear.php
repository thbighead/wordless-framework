<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidYear extends DomainException
{
    public function __construct(public readonly int $year, ?Throwable $previous = null)
    {
        parent::__construct(
            "Wordpress year parameter must be four digits integer, provided $this->year.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
