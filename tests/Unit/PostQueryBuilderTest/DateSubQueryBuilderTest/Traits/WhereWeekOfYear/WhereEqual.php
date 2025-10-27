<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereWeekOfYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidWeek;

trait WhereEqual
{
    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereValidWeekOfYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereValidWeekOfYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'w' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereWeekOfYearEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereNegativeWeekOfYearQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidWeek
     * @throws ReflectionException
     */
    public function testWhereEqualGreaterInvalidWeekQuery(): void
    {
        $this->expectException(InvalidWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereWeekOfYearEqual(90));
    }
}
