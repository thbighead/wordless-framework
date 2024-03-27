<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;

trait WhereNotEqual
{
    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereNotEqualValidDayOfYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofyear' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfYearNotEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereNotEqualValidDayOfYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofyear' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::not_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfYearNotEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereNotEqualNegativeDayOfYearQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfYearNotEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereNotEqualGreaterInvalidDayOfYearQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfYearNotEqual(400));
    }
}
