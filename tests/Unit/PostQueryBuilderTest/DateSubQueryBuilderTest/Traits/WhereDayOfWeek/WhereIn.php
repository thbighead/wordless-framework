<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeek;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

trait WhereIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereInValidDayOfWeeksQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek' => [2],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIn([2]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereInMultipleValidDayOfWeeksQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek' => [2, 3],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIn([2, 3]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereInInvalidGreaterDayOfWeeksQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIn([400]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereInInvalidLessDayOfWeeksQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereInEmptyArrayDayOfWeeksQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereInMultipleInvalidDayOfWeeksQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIn([-10, 500]));
    }
}
