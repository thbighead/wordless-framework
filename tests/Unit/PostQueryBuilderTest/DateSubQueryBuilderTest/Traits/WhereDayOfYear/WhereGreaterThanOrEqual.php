<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfYear;

trait WhereGreaterThanOrEqual
{
    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidDayOfYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofyear' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfYearGreaterThanOrEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidDayOfYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofyear' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfYearGreaterThanOrEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualNegativeDayOfYearQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfYearGreaterThanOrEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidDayOfYear
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualInvalidDayOfYearQuery(): void
    {
        $this->expectException(InvalidDayOfYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfYearGreaterThanOrEqual(400));
    }
}
