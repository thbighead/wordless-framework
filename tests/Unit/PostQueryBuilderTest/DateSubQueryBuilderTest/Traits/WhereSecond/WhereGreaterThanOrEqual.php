<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;

trait WhereGreaterThanOrEqual
{
    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidSecondQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'second' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondGreaterThanOrEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidSecondWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'second' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereSecondGreaterThanOrEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualNegativeSecondQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondGreaterThanOrEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualInvalidSecondQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondGreaterThanOrEqual(150));
    }
}
