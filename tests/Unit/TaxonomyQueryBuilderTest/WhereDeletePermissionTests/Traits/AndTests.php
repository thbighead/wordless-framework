<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereDeletePermissionTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testAndWhereDeletePermission(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereDeletePermission('capability');

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['delete_cap' => 'capability'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testAndWhereDeletePermissionWhereSameAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereDeletePermission('capability')
            ->whereDeletePermission('capability');

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['delete_cap' => 'capability'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testAndWhereDeletePermissionWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereDeletePermission('capability_1')
            ->whereDeletePermission('capability_2');

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['delete_cap' => 'capability_2'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testAndWhereDeletePermissionWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereName('name')
            ->onlyDefault()
            ->whereDeletePermission('capability');

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'delete_cap' => 'capability',
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     */
    public function testAndWhereDeletePermissionWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::make()->whereDeletePermission('');
    }
}
