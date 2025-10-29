<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidSecond extends DomainException
{
    public function __construct(public readonly int $second, ?Throwable $previous = null)
    {
        parent::__construct(
            "Second must be a integer and between 0 and 60, provided $this->second.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
