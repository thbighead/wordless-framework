<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class EmptyQueryBuilderArguments extends InvalidArgumentException
{
    public function __construct(public readonly string $query_builder, ?Throwable $previous = null)
    {
        parent::__construct(
            "No arguments provided to $query_builder object.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
