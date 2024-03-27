<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;

trait WhereLessThanOrEqual
{
    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => 2000,
                    'column' => Column::post_date->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearLessThanOrEqual(2000))
        );
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualValidYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => 2000,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::less_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereYearLessThanOrEqual(2000, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualZeroYearQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearLessThanOrEqual(0));
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereLessThanOrEqualNegativeYearQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearLessThanOrEqual(-2000));
    }
}
