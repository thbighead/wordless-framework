<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYearMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYearMonth;

trait WhereIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereInvalidYearMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => [200010],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthIn([200010]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereInMultipleValidYearMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => [200010, 201010],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthIn([200010, 201010]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereInInvalidGreaterYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthIn([1555555]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereInInvalidLessYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthIn([8010]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereInInvalidZeroYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthIn([0]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereInEmptyArrayYearMonthsQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereInMultipleInvalidYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthIn([2000, 3, 16]));
    }
}
