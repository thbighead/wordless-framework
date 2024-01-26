<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;

trait Year
{
    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereValidYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                ['year' => 2024]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder())->whereYear(2024))
        );
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testOrWhereValidYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::or->value,
                ['year' => 2024]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder(Relation::or))->whereYear(2024))
        );
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereInvalidLessYearQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder())->whereYear(200));
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereInvalidGreaterYearQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder())->whereYear(20000));
    }
}
