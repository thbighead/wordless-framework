<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInAdminMenuTests\Traits;

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
    public function testNotOnlyHiddenInAdminMenu(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)->onlyHiddenFromAdminMenu();

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyHiddenInAdminMenuWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)
            ->onlyAvailableInAdminMenu()
            ->onlyHiddenFromAdminMenu();

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testNotOnlyHiddenInAdminMenuWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)
            ->whereName('name')
            ->onlyDefault()
            ->onlyHiddenFromAdminMenu();

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'show_ui' => false,
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}
