<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereWeekOfYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;

trait WhereGreaterThanOrEqual
{
    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidWeekOfYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearGreaterThanOrEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidWeekOfYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereWeekOfYearGreaterThanOrEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualNegativeWeekOfYearQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearGreaterThanOrEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualInvalidWeekQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearGreaterThanOrEqual(150));
    }
}
