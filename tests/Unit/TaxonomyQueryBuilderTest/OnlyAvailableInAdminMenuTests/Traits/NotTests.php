<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInAdminMenuTests\Traits;

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
    public function testNotOnlyAvailableInAdminMenu(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)->onlyAvailableInAdminMenu();

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyAvailableInAdminMenuWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->onlyAvailableInAdminMenu()
            ->onlyAvailableInAdminMenu();

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testNotOnlyAvailableInAdminMenuWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereName('name')
            ->onlyDefault()
            ->onlyAvailableInAdminMenu();

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'show_ui' => true,
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}
