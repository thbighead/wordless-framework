<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest;

use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\Month;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\Year;
use ReflectionException;
use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\TryBuildEmptyDateSubQuery;

class DateSubQueryBuilderTest extends QueryBuilderTestCase
{
    use Month;
    use Year;

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testEmptyQuery()
    {
        $this->expectException(TryBuildEmptyDateSubQuery::class);
        $this->buildArgumentsFromQueryBuilder(new DateSubQueryBuilder());
    }
}
