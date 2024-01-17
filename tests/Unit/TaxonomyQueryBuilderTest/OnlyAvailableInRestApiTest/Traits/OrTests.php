<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInRestApiTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\OrComparison;

trait OrTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyAvailableInRestApi(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->orOnlyAvailableInRestApi();

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_in_rest' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyAvailableInRestApiWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orOnlyHiddenFromRestApi()
            ->orOnlyAvailableInRestApi();

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

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
    public function testOrOnlyAvailableInRestApiWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orWhereName('name')
            ->orOnlyDefault()
            ->orOnlyAvailableInRestApi();

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

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
