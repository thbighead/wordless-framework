<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInRestApiTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait NotTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyHiddenInRestApi(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)->onlyHiddenFromRestApi();

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_in_rest' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyHiddenInRestApiWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->onlyAvailableInRestApi()
            ->onlyHiddenFromRestApi();

        $this->assertNotOperator($taxonomyQueryBuilder);

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
    public function testNotOnlyHiddenInRestApiWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereName('name')
            ->onlyDefault()
            ->onlyHiddenFromRestApi();

        $this->assertNotOperator($taxonomyQueryBuilder);

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
