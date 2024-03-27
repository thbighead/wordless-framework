<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereWeekOfYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;

trait WhereNotEqual
{
    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereNotEqualValidWeekOfYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearNotEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereNotEqualValidWeekOfYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::not_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereWeekOfYearNotEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereNotEqualNegativeWeekOfYearQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearNotEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereNotEqualGreaterInvalidWeekQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearNotEqual(130));
    }
}
