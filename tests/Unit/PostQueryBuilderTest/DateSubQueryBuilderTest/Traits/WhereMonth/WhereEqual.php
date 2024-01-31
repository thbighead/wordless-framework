<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;

trait WhereEqual
{
    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereValidMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'month' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereValidMonthWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'month' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereMonthEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereZeroMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthEqual(0));
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereNegativeMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereEqualGreaterThanTwelveMonthQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthEqual(13));
    }
}
