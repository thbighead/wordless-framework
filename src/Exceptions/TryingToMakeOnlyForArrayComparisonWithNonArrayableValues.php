<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;

class TryingToMakeOnlyForArrayComparisonWithNonArrayableValues extends Exception
{
    private string $comparison;
    /** @var mixed */
    private $values;

    public function __construct(string $comparison, $values, Throwable $previous = null)
    {
        $this->comparison = $comparison;
        $this->values = $values;

        parent::__construct('Trying to make a '
            . MetaSubQueryBuilder::class
            . " '$comparison' comparison with non arrayable (array or CSV) value", 0, $previous);
    }

    public function getComparison(): string
    {
        return $this->comparison;
    }

    public function getValues()
    {
        return $this->values;
    }
}
