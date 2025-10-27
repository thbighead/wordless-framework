<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYearMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYearMonth;

trait WhereGreaterThanOrEqual
{
    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidYearMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => 200010,
                    'column' => Column::post_date->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthGreaterThanOrEqual(200010))
        );
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidYearMonthWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => 200010,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereYearMonthGreaterThanOrEqual(200010, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualZeroYearMonthQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthGreaterThanOrEqual(0));
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualNegativeYearMonthQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthGreaterThanOrEqual(-2000));
    }
}
