<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;

trait WhereNotBetween
{
    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereNotBetweenValidDayOfMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => [10, 11],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfMonthNotBetween(10, 11))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidGreaterDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfMonthNotBetween(500, 15));
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidLessDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfMonthNotBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereNotBetweenMultipleInvalidDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfMonthNotBetween(-1, 400));
    }
}
