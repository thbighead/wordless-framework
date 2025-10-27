<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;

trait WhereNotBetween
{
    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotBetweenValidMinutesQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => [10, 11],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereMinuteNotBetween(10, 11))
        );
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidGreaterMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereMinuteNotBetween(100, 15));
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotBetweenInvalidLessMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereMinuteNotBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotBetweenMultipleInvalidMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereMinuteNotBetween(-1, 150));
    }
}
