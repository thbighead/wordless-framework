<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Column;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Enums\Relation;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\EmptyDateArgument;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\Exceptions\InvalidMinute;

trait WhereIn
{
    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereInValidMinutesQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => [10],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteIn([10]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereInMultipleValidMinutesQuery(): void
    {
        $this->assertEquals(
            [
                'relation' => Relation::and->value,
                [
                    'minute' => [10, 8],
                    'column' => Column::post_date->name,
                    'compare' => Compare::in->value
                ]
            ],
            $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteIn([10, 8]))
        );
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereInInvalidGreaterMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteIn([100]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereInInvalidLessMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteIn([-2]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereInEmptyArrayMinutesQuery(): void
    {
        $this->expectException(EmptyDateArgument::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteIn([]));
    }

    /**
     * @return void
     * @throws EmptyDateArgument
     * @throws InvalidMinute
     * @throws ReflectionException
     */
    public function testWhereInMultipleInvalidMinutesQuery(): void
    {
        $this->expectException(InvalidMinute::class);
        $this->buildArgumentsFromQueryBuilder((new DateSubQueryBuilder)->whereMinuteIn([-10, 100]));
    }
}
