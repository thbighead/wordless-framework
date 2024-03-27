<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidSecond;

trait WhereNotIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereNotInValidSecondsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'second' => [10],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondNotIn([10]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleValidSecondsQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'second' => [10, 8],
                    'column' => Column::post_date->name,
                    'compare' => Compare::not_in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondNotIn([10, 8]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidGreaterSecondsQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondNotIn([100]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereNotInInvalidLessSecondsQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondNotIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereNotInEmptyArraySecondsQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondNotIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidSecond
     * @throws ReflectionException
     */
    public function testWhereNotInMultipleInvalidSecondsQuery(): void
    {
        $this->expectException(InvalidSecond::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereSecondNotIn([-10, 5, 200]));
    }
}
