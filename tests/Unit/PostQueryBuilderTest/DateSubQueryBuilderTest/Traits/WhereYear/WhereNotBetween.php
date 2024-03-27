<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;

trait WhereNotBetween
{
    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotBetweenValidYearsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => [2000, 2020],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereYearNotBetween(2000, 2020))
        );
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidGreaterYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereYearNotBetween(2000, 20000));
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidLessYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereYearNotBetween(-2, 2000));
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidZeroYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereYearNotBetween(0, 2000));
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotBetweenMultipleInvalidYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereYearNotBetween(-1, 0));
    }
}
