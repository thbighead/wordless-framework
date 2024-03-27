<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest;

use ReflectionException;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfMonth;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeek;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfYear;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereHour;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereWeekOfYear;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYearMonth;
use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;

class DateSubQueryBuilderTest extends QueryBuilderTestCase
{
    use WhereDayOfMonth;
    use WhereDayOfWeek;
    use WhereDayOfWeekIso;
    use WhereDayOfYear;
    use WhereHour;
    use WhereMinute;
    use WhereMonth;
    use WhereSecond;
    use WhereYear;
    use WhereYearMonth;
    use WhereWeekOfYear;

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
