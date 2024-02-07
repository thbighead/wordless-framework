<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInRestApiTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyHiddenInRestApi(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->onlyHiddenFromRestApi();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_in_rest' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyHiddenInRestApiWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->onlyAvailableInRestApi()
            ->onlyHiddenFromRestApi();

        $this->assertAndOperator($taxonomyQueryBuilder);

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
    public function testAndOnlyHiddenInRestApiWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->whereName('name')
            ->onlyDefault()
            ->onlyHiddenFromRestApi();

        $this->assertAndOperator($taxonomyQueryBuilder);

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
