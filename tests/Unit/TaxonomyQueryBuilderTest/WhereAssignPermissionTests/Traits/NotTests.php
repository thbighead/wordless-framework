<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait NotTests
{
    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testNotWhereAssignPermissionTest(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereAssignPermission('capability');

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['assign_cap' => 'capability'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testNotWhereAssignPermissionWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereAssignPermission('capability')
            ->whereAssignPermission('capability');

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['assign_cap' => 'capability'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testNotWhereAssignPermissionWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereName('name')
            ->onlyDefault()
            ->whereAssignPermission('capability');

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'assign_cap' => 'capability',
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testNotWhereAssignPermissionWhitLargeStringArgument(): void
    {
        $capability = str_repeat('a', 256 * 1024);

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereAssignPermission($capability);

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(['assign_cap' => $capability], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     */
    public function testNotWhereAssignPermissionWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::make(operator: Operator::not)->whereAssignPermission('');
    }
}
