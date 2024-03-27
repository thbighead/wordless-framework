<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions\InvalidYear;

trait WhereEqual
{
    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereValidYearQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => 2000,
                    'column' => Column::post_date->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearEqual(2000))
        );
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereValidYearWithDifferentColumnQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'year' => 2000,
                    'column' => Column::post_modified->name,
                    'compare' => Compare::equals->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)
                ->whereYearEqual(2000, Column::post_modified))
        );
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereZeroYearQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearEqual(0));
    }

    /**
     * @return void
     * @throws InvalidYear
     * @throws ReflectionException
     */
    public function testWhereNegativeYearQuery(): void
    {
        $this->expectException(InvalidYear::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereYearEqual(-2000));
    }
}
