<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;

trait WhereBetween
{
    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereBetweenValidSecondsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'second' => [10, 11],
                    'column' => Column::post_date->name,
                    'compare' => Compare::between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereSecondBetween(10, 11))
        );
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidGreaterSecondsQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereSecondBetween(10, 70));
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidLessSecondsQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereSecondBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereBetweenMultipleInvalidSecondsQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereSecondBetween(-1, 85));
    }
}
