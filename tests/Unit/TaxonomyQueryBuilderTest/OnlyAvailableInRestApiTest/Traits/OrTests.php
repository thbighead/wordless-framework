<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInRestApiTest\Traits;

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
    public function testOrOnlyAvailableInRestApi(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)->onlyAvailableInRestApi();

        $this->assertOrOperator($taxonomyQueryBuilder);

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
            ->onlyHiddenFromRestApi()
            ->onlyAvailableInRestApi();

        $this->assertOrOperator($taxonomyQueryBuilder);

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
            ->whereName('name')
            ->onlyDefault()
            ->onlyAvailableInRestApi();

        $this->assertOrOperator($taxonomyQueryBuilder);

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
