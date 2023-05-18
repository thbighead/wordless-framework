<?php

namespace Wordless\Exceptions;

use Throwable;
use UnexpectedValueException;
use Wordless\Application\Helpers\GetType;
use Wordless\Infrastructure\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;

class UnexpectedTaxonomySubQueryClosureReturn extends UnexpectedValueException
{
    private $closure_return_value;

    public function __construct($closure_return_value, Throwable $previous = null)
    {
        $this->closure_return_value = $closure_return_value;

        parent::__construct(
            'Callables passed as parameters to methods of '
            . TaxonomySubQueryBuilder::class
            . ' subclasses must return a '
            . TaxonomySubQueryBuilder::class
            . ' object or an array. But a '
            . GetType::of($this->closure_return_value)
            . ' was given.',
            0,
            $previous
        );
    }

    /**
     * @return mixed
     */
    public function getClosureReturnValue()
    {
        return $this->closure_return_value;
    }
}
