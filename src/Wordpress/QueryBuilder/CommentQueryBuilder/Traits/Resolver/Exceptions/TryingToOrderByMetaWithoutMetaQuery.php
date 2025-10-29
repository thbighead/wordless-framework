<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class TryingToOrderByMetaWithoutMetaQuery extends InvalidArgumentException
{
    public function __construct(public readonly array $arguments, ?Throwable $previous = null)
    {
        parent::__construct(
            'Cannot use CommentQueryBuilder::orderByMeta without also use CommentQueryBuilder::whereMeta.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
