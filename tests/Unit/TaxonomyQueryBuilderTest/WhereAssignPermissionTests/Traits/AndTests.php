<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testAndWhereAssignPermissionTest(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()->whereAssignPermission('capability');

        $this->assertAndOperator($taxonomyQueryBuilder);

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
    public function testAndWhereAssignPermissionWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereAssignPermission('capability')
            ->whereAssignPermission('capability');

        $this->assertAndOperator($taxonomyQueryBuilder);

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
    public function testAndWhereAssignPermissionWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereName('name')
            ->onlyDefault()
            ->whereAssignPermission('capability');

        $this->assertAndOperator($taxonomyQueryBuilder);

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
    public function testAndWhereAssignPermissionWhitLargeStringArgument(): void
    {
        $capability = str_repeat('a', 256 * 1024);

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()->whereAssignPermission($capability);

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(['assign_cap' => $capability], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     */
    public function testAndWhereAssignPermissionWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::make()->whereAssignPermission('');
    }
}
