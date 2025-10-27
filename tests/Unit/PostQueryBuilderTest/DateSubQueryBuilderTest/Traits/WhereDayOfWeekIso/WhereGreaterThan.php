<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidDayOfWeek;

trait WhereGreaterThan
{
    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereGreaterThanValidDayOfWeekIsoQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek_iso' => 2,
                    'column' => Column::post_date->name,
                    'compare' => Compare::greater_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoGreaterThan(2))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereGreaterThanValidDayOfWeekIsoWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'dayofweek_iso' => 2,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::greater_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereDayOfWeekIsoGreaterThan(2, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereGreaterThanNegativeDayOfWeekIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoGreaterThan(-10));
    }

    /**
     * @return void
     * @throws InvalidDayOfWeek
     * @throws ReflectionException
     */
    public function testWhereGreaterInvalidDayOfWeekIsoQuery(): void
    {
        $this->expectException(InvalidDayOfWeek::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereDayOfWeekIsoGreaterThan(500));
    }
}
