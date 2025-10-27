<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;

trait WhereLessThanOrEqual
{
    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidSecondQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'second' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondLessThanOrEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidSecondWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'second' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereSecondLessThanOrEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualNegativeSecondQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondLessThanOrEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualGreaterInvalidTwelveSecondQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondLessThanOrEqual(65));
    }
}
