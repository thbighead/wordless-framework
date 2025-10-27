<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

trait WhereNotBetween
{
    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotBetweenValidDayOfWeeksIsoQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek_iso' => [2, 3],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfWeekIsoNotBetween(2, 3))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidGreaterDayOfWeeksIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfWeekIsoNotBetween(500, 15));
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidLessDayOfWeeksIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfWeekIsoNotBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotBetweenMultipleInvalidDayOfWeeksIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfWeekIsoNotBetween(-1, 400));
    }
}
