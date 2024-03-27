<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeek;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

trait WhereNotIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInValidDayOfWeeksQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek' => [2],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekNotIn([2]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleValidDayOfWeeksQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek' => [2, 3],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekNotIn([2, 3]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidGreaterDayOfWeeksQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekNotIn([500]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidLessDayOfWeeksQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekNotIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInEmptyArrayDayOfWeeksQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekNotIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleInvalidDayOfWeeksQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekNotIn([-10, 5, 500]));
    }
}
