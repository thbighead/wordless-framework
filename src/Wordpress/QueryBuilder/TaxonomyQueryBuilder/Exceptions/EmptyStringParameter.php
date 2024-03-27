<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class EmptyStringParameter extends ErrorException
{
    public function __construct(public readonly string $method_name, ?Throwable $previous = null)
    {
        parent::__construct(
            "Empty string passed to method '$this->method_name' as parameter.",
            ExceptionCode::development_error->value,
            previous: $previous
        );
    }
}
