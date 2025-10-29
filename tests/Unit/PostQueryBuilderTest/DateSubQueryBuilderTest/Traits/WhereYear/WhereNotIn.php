<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;

trait WhereNotIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotInvalidYearsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => [2000],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearNotIn([2000]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleValidYearsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => [2000, 8000],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearNotIn([2000, 8000]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidGreaterYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearNotIn([20000]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidLessYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearNotIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidZeroYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearNotIn([0]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotInEmptyArrayYearsQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearNotIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleInvalidYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearNotIn([2000, 3, 0]));
    }
}
