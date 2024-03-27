<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;

trait WhereLessThanOrEqual
{
    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidMinuteQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteLessThanOrEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidMinuteWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereMinuteLessThanOrEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualNegativeMinuteQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteLessThanOrEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualGreaterInvalidTwelveMinuteQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteLessThanOrEqual(65));
    }
}
