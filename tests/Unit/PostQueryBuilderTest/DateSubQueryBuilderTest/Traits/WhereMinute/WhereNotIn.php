<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;

trait WhereNotIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotInValidMinutesQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => [10],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteNotIn([10]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleValidMinutesQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => [10, 8],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteNotIn([10, 8]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidGreaterMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteNotIn([100]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidLessMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteNotIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotInEmptyArrayMinutesQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteNotIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleInvalidMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteNotIn([-10, 5, 200]));
    }
}
