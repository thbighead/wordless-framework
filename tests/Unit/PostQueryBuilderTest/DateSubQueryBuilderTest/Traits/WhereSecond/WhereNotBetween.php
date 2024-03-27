<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;

trait WhereNotBetween
{
    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereNotBetweenValidSecondsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'second' => [10, 11],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereSecondNotBetween(10, 11))
        );
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidGreaterSecondsQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereSecondNotBetween(100, 15));
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidLessSecondsQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereSecondNotBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereNotBetweenMultipleInvalidSecondsQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereSecondNotBetween(-1, 150));
    }
}
