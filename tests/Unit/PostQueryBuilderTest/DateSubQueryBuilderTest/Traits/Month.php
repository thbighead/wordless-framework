<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;

trait Month
{
    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereValidMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                ['monthnum' => 10]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder())->whereMonth(10))
        );
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testOrWhereValidMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::or->value,
                ['monthnum' => 10]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder(Relation::or))->whereMonth(10))
        );
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereInvalidLessMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder())->whereMonth(-10));
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereInvalidEqualZeroMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder())->whereMonth(0));
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereInvalidGreaterMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder())->whereMonth(20000));
    }
}
