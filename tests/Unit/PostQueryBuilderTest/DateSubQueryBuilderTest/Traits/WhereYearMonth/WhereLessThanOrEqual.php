<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYearMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYearMonth;

trait WhereLessThanOrEqual
{
    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidYearMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => 200010,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthLessThanOrEqual(200010))
        );
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidYearMonthWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => 200010,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereYearMonthLessThanOrEqual(200010, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualZeroYearMonthQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthLessThanOrEqual(0));
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualNegativeYearMonthQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthLessThanOrEqual(-2000));
    }
}
