<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInRestApiTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait OrTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyHiddenInRestApi(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)->onlyHiddenFromRestApi();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_in_rest' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyHiddenInRestApiWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->onlyAvailableInRestApi()
            ->onlyHiddenFromRestApi();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_in_rest' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testOrOnlyHiddenInRestApiWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->whereName('name')
            ->onlyDefault()
            ->onlyHiddenFromRestApi();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'show_in_rest' => false,
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}
