<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;

trait WhereGreaterThan
{
    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereGreaterThanValidDayOfMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::greater_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthGreaterThan(10))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereGreaterThanValidDayOfMonthWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::greater_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfMonthGreaterThan(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereGreaterThanNegativeDayOfMonthQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthGreaterThan(-10));
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereGreaterInvalidDayOfMonthQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthGreaterThan(500));
    }
}
