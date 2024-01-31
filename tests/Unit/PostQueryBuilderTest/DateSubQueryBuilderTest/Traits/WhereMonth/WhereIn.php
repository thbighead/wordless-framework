<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMonth;

trait WhereIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereInValidMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'month' => [10],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthIn([10]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereInMultipleValidMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'month' => [10, 8],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthIn([10, 8]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereInInvalidGreaterMonthsQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthIn([15]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereInInvalidLessMonthsQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereInInvalidZeroMonthsQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthIn([0]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereInEmptyArrayMonthsQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMonth
     * @throws ReflectionException
     */
    public function testWhereInMultipleInvalidMonthsQuery(): void
    {
        $this->expectException(InvalidMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMonthIn([10, 3, 16]));
    }
}
