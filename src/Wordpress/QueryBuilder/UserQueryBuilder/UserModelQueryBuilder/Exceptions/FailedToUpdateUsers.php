<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\UserModelQueryBuilder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToUpdateUsers extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Failed to update users.', ExceptionCode::development_error->value, $previous);
    }
}
