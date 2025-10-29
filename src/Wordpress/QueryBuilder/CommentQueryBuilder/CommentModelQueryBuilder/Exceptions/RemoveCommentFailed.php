<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\CommentModelQueryBuilder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class RemoveCommentFailed extends RuntimeException
{
    public function __construct(public readonly string $method, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to $this->method comments.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
