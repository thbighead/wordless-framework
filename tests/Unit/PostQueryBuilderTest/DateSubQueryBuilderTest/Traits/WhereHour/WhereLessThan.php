<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereHour;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;

trait WhereLessThan
{
    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereLessThanValidHourQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'hour' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayLessThan(10))
        );
    }

    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereLessThanValidHourWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'hour' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereHourOfDayLessThan(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereLessThanNegativeHourQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayLessThan(-10));
    }
}
