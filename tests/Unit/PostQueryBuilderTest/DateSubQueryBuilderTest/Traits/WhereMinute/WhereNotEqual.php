<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;

trait WhereNotEqual
{
    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotEqualValidMinuteQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteNotEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotEqualValidMinuteWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::not_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereMinuteNotEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotEqualNegativeMinuteQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteNotEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotEqualGreaterInvalidMinuteQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteNotEqual(130));
    }
}
