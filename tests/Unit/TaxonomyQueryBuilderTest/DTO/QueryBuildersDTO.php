<?php declare(strict_types=1);

namespace TaxonomyQueryBuilderTest\DTO;

use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;

readonly class QueryBuildersDTO
{
    public TaxonomyQueryBuilder $queryBuilder;

    public function __construct(
        public ResultFormat $format,
        public Operator     $operator
    )
    {
        $this->queryBuilder = new TaxonomyQueryBuilder($this->format, $this->operator);
    }
}
