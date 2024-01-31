<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest;

use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereHour;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear;
use ReflectionException;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth;
use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;

class DateSubQueryBuilderTest extends QueryBuilderTestCase
{
    use WhereHour;
    use WhereMonth;
    use WhereYear;

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
