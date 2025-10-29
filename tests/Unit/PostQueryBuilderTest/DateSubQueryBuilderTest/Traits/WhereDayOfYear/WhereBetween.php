<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;

trait WhereBetween
{
    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereBetweenValidDayOfYearsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofyear' => [10, 11],
                    'column' => Column::post_date->name,
                    'compare' => Compare::between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfYearBetween(10, 11))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidGreaterDayOfYearsQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfYearBetween(500, 70));
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidLessDayOfYearsQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfYearBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereBetweenMultipleInvalidDayOfYearsQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfYearBetween(-1, 500));
    }
}
