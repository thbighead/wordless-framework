<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;

trait WhereBetween
{
    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereBetweenValidMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'month' => [10, 11],
                    'column' => Column::post_date->name,
                    'compare' => Compare::between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereMonthBetween(10, 11))
        );
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidGreaterMonthsQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereMonthBetween(10, 15));
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidLessMonthsQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereMonthBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidZeroMonthsQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereMonthBetween(0, 10));
    }

    /**
     * @return void
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereBetweenMultipleInvalidMonthsQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereMonthBetween(-1, 0));
    }
}
