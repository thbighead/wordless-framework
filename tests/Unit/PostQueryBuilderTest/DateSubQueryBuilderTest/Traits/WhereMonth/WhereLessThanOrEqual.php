<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;

trait WhereLessThanOrEqual
{
    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'month' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthLessThanOrEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidMonthWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'month' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereMonthLessThanOrEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualZeroMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthLessThanOrEqual(0));
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualNegativeMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthLessThanOrEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualGreaterThanTwelveMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthLessThanOrEqual(13));
    }
}
