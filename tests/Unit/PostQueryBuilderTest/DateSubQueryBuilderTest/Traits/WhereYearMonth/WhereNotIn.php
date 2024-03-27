<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYearMonth;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYearMonth;

trait WhereNotIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotInvalidYearMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => [200010],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthNotIn([200010]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleValidYearMonthsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'm' => [200010, 800010],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthNotIn([200010, 800010]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidGreaterYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthNotIn([2000010]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidLessYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthNotIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidZeroYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthNotIn([0]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotInEmptyArrayYearMonthsQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthNotIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYearMonth
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleInvalidYearMonthsQuery(): void
    {
        $this->expectException(InvalidYearMonth::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearMonthNotIn([2000, 3, 0]));
    }
}
