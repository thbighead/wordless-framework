<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;

trait WhereBetween
{
    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereBetweenValidMinutesQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => [10, 11],
                    'column' => Column::post_date->name,
                    'compare' => Compare::between->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereMinuteBetween(10, 11))
        );
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidGreaterMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereMinuteBetween(10, 70));
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereBetweenInvalidLessMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereMinuteBetween(-2, 10));
    }

    /**
     * @return void
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereBetweenMultipleInvalidMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
            ->whereMinuteBetween(-1, 85));
    }
}
