<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;

trait WhereLessThan
{
    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanValidMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'monthnum' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthLessThan(10))
        );
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanValidMonthWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'monthnum' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereMonthLessThan(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanZeroMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthLessThan(0));
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanNegativeMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthLessThan(-10));
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanGreaterThanTwelveMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthLessThan(13));
    }
}
