<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereHour;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;

trait WhereNotIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereNotInvalidHoursQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'hour' => [10],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayNotIn([10]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleValidHoursQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'hour' => [10, 15],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayNotIn([10, 15]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidGreaterHoursQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayNotIn([100]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidLessHoursQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayNotIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereNotInEmptyArrayHoursQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayNotIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleInvalidHoursQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayNotIn([-10, -20, 2000]));
    }
}
