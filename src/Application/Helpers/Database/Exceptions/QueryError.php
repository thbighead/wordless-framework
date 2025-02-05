<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Database\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class QueryError extends ErrorException
{
    public function __construct(string $query, ?Throwable $previous = null)
    {
        parent::__construct(
            "The following query produced an error, please, check the logs: $query",
            ExceptionCode::development_error->value,
            previous: $previous
        );
    }
}
