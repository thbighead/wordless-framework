<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest;

use ReflectionException;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\Month;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\Year;
use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;

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
        $this->expectException(EmptyQueryBuilderArguments::class);
        $this->buildArgumentsFromQueryBuilder(new DateSubQueryBuilder());
    }
}
