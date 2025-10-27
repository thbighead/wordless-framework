<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereWeekOfYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;

trait WhereIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereInValidWeekOfYearsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => [10],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearIn([10]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereInMultipleValidWeekOfYearsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => [10, 8],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearIn([10, 8]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereInInvalidGreaterWeekOfYearsQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearIn([100]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereInInvalidLessWeekOfYearsQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereInEmptyArrayWeekOfYearsQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereInMultipleInvalidWeeksQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearIn([-10, 100]));
    }
}
