<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereHour;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;

trait WhereIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereInvalidHoursQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'hour' => [10],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayIn([10]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereInMultipleValidHoursQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'hour' => [10, 20],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayIn([10, 20]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereInInvalidGreaterHoursQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayIn([30]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereInInvalidLessHoursQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayIn([-10]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereInEmptyArrayHoursQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereInMultipleInvalidHoursQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayIn([-10, 300, 1458]));
    }
}
