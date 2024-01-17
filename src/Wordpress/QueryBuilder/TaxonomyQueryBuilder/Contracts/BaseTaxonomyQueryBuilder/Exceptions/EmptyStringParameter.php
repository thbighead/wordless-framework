<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Exceptions;

use ErrorException;
use Throwable;

class EmptyStringParameter extends ErrorException
{
    public function __construct(public readonly string $method_name, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed on try pass empty string to method \"$method_name\".",
            previous: $previous
        );
    }
}
