<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereHour;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;

trait WhereGreaterThan
{
    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereGreaterThanValidHourQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'hour' => 16,
                    'column' => Column::post_date->name,
                    'compare' => Compare::greater_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayGreaterThan(16))
        );
    }

    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereGreaterThanValidHourWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'hour' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::greater_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereHourOfDayGreaterThan(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereGreaterThanNegativeHourQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayGreaterThan(-10));
    }
}
