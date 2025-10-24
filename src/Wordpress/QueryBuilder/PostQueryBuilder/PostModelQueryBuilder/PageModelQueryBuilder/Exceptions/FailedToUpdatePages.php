<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\PageModelQueryBuilder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToUpdatePages extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Failed to update pages.', ExceptionCode::development_error->value, $previous);
    }
}
