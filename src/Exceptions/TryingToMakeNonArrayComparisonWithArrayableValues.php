<?php

namespace Wordless\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;

class TryingToMakeNonArrayComparisonWithArrayableValues extends InvalidArgumentException
{
    public function __construct(private readonly string $comparison, private $values, ?Throwable $previous = null)
    {
        parent::__construct(
            'Trying to make a '
            . MetaSubQueryBuilder::class
            . " '$this->comparison' comparison with arrayable (array or CSV) value",
            ExceptionCode::development_error->value,
            $previous
        );
    }

    public function getComparison(): string
    {
        return $this->comparison;
    }

    public function getValues(): mixed
    {
        return $this->values;
    }
}
