<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;

trait WhereGreaterThanOrEqual
{
    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidMinuteQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteGreaterThanOrEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidMinuteWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereMinuteGreaterThanOrEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualNegativeMinuteQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteGreaterThanOrEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualInvalidMinuteQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteGreaterThanOrEqual(150));
    }
}
