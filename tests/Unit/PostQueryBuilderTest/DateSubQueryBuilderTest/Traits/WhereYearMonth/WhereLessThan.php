<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYearMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYearMonth;

trait WhereLessThan
{
    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanValidYearMonthQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => 200010,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthLessThan(200010))
        );
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanValidYearMonthWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => 200010,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereYearMonthLessThan(200010, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanZeroYearMonthQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthLessThan(0));
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereLessThanNegativeYearMonthQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthLessThan(-2000));
    }
}
