<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;

trait WhereLessThan
{
    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereLessThanValidYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => 2000,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearLessThan(2000))
        );
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereLessThanValidYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => 2000,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereYearLessThan(2000, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereLessThanZeroYearQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearLessThan(0));
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereLessThanNegativeYearQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearLessThan(-2000));
    }
}
