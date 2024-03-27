<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

trait WhereNotEqual
{
    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotEqualValidDayOfWeekIsoQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek_iso' => 2,
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoNotEqual(2))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotEqualValidDayOfWeekIsoWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek_iso' => 2,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::not_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfWeekIsoNotEqual(2, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotEqualNegativeDayOfWeekIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoNotEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereNotEqualGreaterInvalidDayOfWeekIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoNotEqual(400));
    }
}
