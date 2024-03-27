<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

trait WhereBetween
{
    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereBetweenValidDayOfWeeksIsoQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek_iso' => [1, 5],
                    'column' => Column::post_date->name,
                    'compare' => Compare::between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfWeekIsoBetween(1, 5))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidGreaterDayOfWeeksIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfWeekIsoBetween(500, 70));
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidLessDayOfWeeksIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfWeekIsoBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereBetweenMultipleInvalidDayOfWeeksIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereDayOfWeekIsoBetween(-1, 500));
    }
}
