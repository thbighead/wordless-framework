<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereHour;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidHour;

trait WhereEqual
{
    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereValidHourQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'hour' => 15,
                    'column' => Column::post_date->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayEqual(15))
        );
    }

    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereValidHourWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'hour' => 14,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereHourOfDayEqual(14, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidHour
     * @throws ReflectionException
     */
    public function testWhereNegativeHourQuery(): void
    {
        $this->expectException(InvalidHour::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereHourOfDayEqual(-20));
    }
}
