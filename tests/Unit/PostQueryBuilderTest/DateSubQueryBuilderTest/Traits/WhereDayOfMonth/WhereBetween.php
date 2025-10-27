<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;

trait WhereBetween
{
    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereBetweenValidDayOfMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => [10, 11],
                    'column' => Column::post_date->name,
                    'compare' => Compare::between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfMonthBetween(10, 11))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidGreaterDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfMonthBetween(500, 70));
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidLessDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfMonthBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereBetweenMultipleInvalidDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfMonthBetween(-1, 500));
    }
}
