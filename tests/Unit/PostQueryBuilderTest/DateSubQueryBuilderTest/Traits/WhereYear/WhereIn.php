<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;

trait WhereIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereInvalidYearsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => [2000],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearIn([2000]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereInMultipleValidYearsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => [2000, 2010],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearIn([2000, 2010]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereInInvalidGreaterYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearIn([15]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereInInvalidLessYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearIn([800]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereInInvalidZeroYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearIn([0]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereInEmptyArrayYearsQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereInMultipleInvalidYearsQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearIn([2000, 3, 16]));
    }
}
