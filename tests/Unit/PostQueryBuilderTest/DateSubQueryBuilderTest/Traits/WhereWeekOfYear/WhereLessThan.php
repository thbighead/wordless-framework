<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereWeekOfYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;

trait WhereLessThan
{
    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereLessThanValidWeekOfYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearLessThan(10))
        );
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereLessThanValidWeekOfYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereWeekOfYearLessThan(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereLessThanNegativeWeekOfYearQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearLessThan(-10));
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereLessThanGreaterInvalidWeekQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearLessThan(95));
    }
}
