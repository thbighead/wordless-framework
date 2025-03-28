<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInRestApiTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyAvailableInRestApi(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()->onlyAvailableInRestApi();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_in_rest' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyAvailableInRestApiWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->onlyHiddenFromRestApi()
            ->onlyAvailableInRestApi();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_in_rest' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testAndOnlyAvailableInRestApiWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereName('name')
            ->onlyDefault()
            ->onlyAvailableInRestApi();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'show_in_rest' => true,
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}
