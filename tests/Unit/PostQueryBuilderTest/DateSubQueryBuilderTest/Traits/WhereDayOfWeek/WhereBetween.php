<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeek;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

trait WhereBetween
{
    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereBetweenValidDayOfWeeksQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek' => [1, 5],
                    'column' => Column::post_date->name,
                    'compare' => Compare::between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfWeekBetween(1, 5))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidGreaterDayOfWeeksQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfWeekBetween(500, 70));
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidLessDayOfWeeksQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfWeekBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereBetweenMultipleInvalidDayOfWeeksQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfWeekBetween(-1, 500));
    }
}
