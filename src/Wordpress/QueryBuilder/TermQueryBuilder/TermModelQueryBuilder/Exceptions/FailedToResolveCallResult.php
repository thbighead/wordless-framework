<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder;
use WP_Term;

class FailedToResolveCallResult extends RuntimeException
{
    public function __construct(
        public readonly string                                              $method_called,
        public readonly array                                               $arguments,
        public readonly bool|int|string|array|WP_Term|TermQueryBuilder|null $result,
        ?Throwable                                                          $previous = null
    )
    {
        parent::__construct(
            "Could not resolve what to do with the result of $this->method_called",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
