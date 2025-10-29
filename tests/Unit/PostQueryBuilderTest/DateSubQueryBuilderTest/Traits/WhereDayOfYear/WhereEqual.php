<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;

trait WhereEqual
{
    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereValidDayOfYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofyear' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfYearEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereValidDayOfYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofyear' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfYearEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereNegativeDayOfYearQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfYearEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereEqualGreaterInvalidDayOfYearQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfYearEqual(400));
    }
}
