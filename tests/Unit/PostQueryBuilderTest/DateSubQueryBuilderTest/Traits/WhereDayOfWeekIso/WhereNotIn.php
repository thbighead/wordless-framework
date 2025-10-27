<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

trait WhereNotIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInValidDayOfWeeksIsoQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek_iso' => [2],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoNotIn([2]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleValidDayOfWeeksIsoQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek_iso' => [2, 3],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoNotIn([2, 3]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidGreaterDayOfWeeksIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoNotIn([500]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidLessDayOfWeeksIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoNotIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInEmptyArrayDayOfWeeksIsoQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoNotIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleInvalidDayOfWeeksIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoNotIn([-10, 5, 500]));
    }
}
