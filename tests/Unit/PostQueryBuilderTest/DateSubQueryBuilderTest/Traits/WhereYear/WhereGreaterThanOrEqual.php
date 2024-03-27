<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;

trait WhereGreaterThanOrEqual
{
    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => 2000,
                    'column' => Column::post_date->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearGreaterThanOrEqual(2000))
        );
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualValidYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => 2000,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::greater_than_or_equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereYearGreaterThanOrEqual(2000, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualZeroYearQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearGreaterThanOrEqual(0));
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereGreaterThanOrEqualNegativeYearQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearGreaterThanOrEqual(-2000));
    }
}
