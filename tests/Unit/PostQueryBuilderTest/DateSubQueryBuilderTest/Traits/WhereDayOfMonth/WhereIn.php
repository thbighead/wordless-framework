<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;

trait WhereIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereInValidDayOfMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => [10],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthIn([10]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereInMultipleValidDayOfMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => [10, 8],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthIn([10, 8]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereInInvalidGreaterDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthIn([400]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereInInvalidLessDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereInEmptyArrayDayOfMonthsQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereInMultipleInvalidDayOfMonthsQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthIn([-10, 500]));
    }
}
