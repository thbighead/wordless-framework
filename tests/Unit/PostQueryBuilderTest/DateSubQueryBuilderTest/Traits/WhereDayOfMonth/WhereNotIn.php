<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;

trait WhereNotIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereNotInValidDayOfMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => [10],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthNotIn([10]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleValidDayOfMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => [10, 8],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthNotIn([10, 8]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidGreaterDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthNotIn([500]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidLessDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthNotIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereNotInEmptyArrayDayOfMonthsQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthNotIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleInvalidDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthNotIn([-10, 5, 500]));
    }
}
