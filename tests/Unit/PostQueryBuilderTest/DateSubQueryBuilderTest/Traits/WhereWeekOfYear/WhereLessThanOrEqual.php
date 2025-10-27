<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereWeekOfYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;

trait WhereLessThanOrEqual
{
    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidWeekOfYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearLessThanOrEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidWeekOfYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereWeekOfYearLessThanOrEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualNegativeWeekOfYearQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearLessThanOrEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualGreaterInvalidTwelveWeekOfYearQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearLessThanOrEqual(65));
    }
}
