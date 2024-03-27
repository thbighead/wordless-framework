<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfMonth;

trait WhereLessThanOrEqual
{
    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidDayOfMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthLessThanOrEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidDayOfMonthWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'day' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfMonthLessThanOrEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualNegativeDayOfMonthQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthLessThanOrEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidDayOfMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualGreaterInvalidTwelveDayOfMonthQuery(): void
    {
        $this->expectException(InvalidDayOfMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfMonthLessThanOrEqual(400));
    }
}
