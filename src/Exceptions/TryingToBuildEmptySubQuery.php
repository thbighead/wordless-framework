<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder\EmptyTaxonomySubQueryBuilder;

class TryingToBuildEmptySubQuery extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct(
            EmptyTaxonomySubQueryBuilder::class . ' can\'t be built.',
            0,
            $previous
        );
    }
}
