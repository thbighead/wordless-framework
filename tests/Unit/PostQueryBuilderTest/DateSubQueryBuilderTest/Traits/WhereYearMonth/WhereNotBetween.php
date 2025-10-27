<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYearMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYearMonth;

trait WhereNotBetween
{
    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotBetweenValidYearMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => [200010, 202010],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereYearMonthNotBetween(200010, 202010))
        );
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidGreaterYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereYearMonthNotBetween(200002255, 200004558));
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidLessYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereYearMonthNotBetween(-2, 2000));
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidZeroYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereYearMonthNotBetween(0, 2000));
    }

    /**
     * @return void
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotBetweenMultipleInvalidYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereYearMonthNotBetween(-1, 0));
    }
}
