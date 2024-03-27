<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereHour;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;

trait WhereBetween
{
    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereBetweenValidHoursQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'hour' => [1, 10],
                    'column' => Column::post_date->name,
                    'compare' => Compare::between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereHourOfDayBetween(1, 10))
        );
    }

    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidGreaterHoursQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereHourOfDayBetween(10, 30));
    }

    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidLessHoursQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereHourOfDayBetween(-1, 10));
    }

    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereBetweenMultipleInvalidHoursQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereHourOfDayBetween(-1, 70));
    }
}
