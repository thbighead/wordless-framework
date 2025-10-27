<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;

trait WhereEqual
{
    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereValidSecondQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'second' => 10,
                    'column' => Column::post_date->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondEqual(10))
        );
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereValidSecondWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'second' => 10,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereSecondEqual(10, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereNegativeSecondQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondEqual(-10));
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereEqualGreaterInvalidSecondQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondEqual(90));
    }
}
