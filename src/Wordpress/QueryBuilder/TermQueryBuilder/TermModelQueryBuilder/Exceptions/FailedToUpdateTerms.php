<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToUpdateTerms extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Failed to update terms.', ExceptionCode::development_error->value, $previous);
    }
}
