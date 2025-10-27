<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYearMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYearMonth;

trait WhereNotEqual
{
    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotEqualValidYearMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => 200010,
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthNotEqual(200010))
        );
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotEqualValidYearMonthWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => 200010,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::not_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereYearMonthNotEqual(200010, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotEqualZeroYearMonthQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthNotEqual(0));
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotEqualNegativeYearMonthQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthNotEqual(-2000));
    }
}
