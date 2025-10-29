<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;

trait WhereNotBetween
{
    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereNotBetweenValidDayOfYearsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofyear' => [10, 11],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfYearNotBetween(10, 11))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidGreaterDayOfYearsQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfYearNotBetween(500, 15));
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidLessDayOfYearsQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfYearNotBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereNotBetweenMultipleInvalidDayOfYearsQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfYearNotBetween(-1, 400));
    }
}
