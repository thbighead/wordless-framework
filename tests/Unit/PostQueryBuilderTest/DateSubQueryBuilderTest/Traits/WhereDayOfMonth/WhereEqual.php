<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;

trait WhereEqual
{
    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereValidDayOfMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereValidDayOfMonthWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfMonthEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereNegativeDayOfMonthQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereEqualGreaterInvalidDayOfMonthQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthEqual(400));
    }
}
