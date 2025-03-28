<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereDeletePermissionTests\Traits;

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
    public function testNotWhereDeletePermission(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereDeletePermission('capability');

        $this->assertNotOperator($taxonomyQueryBuilder);

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
    public function testNotWhereDeletePermissionWhereSameAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereDeletePermission('capability')
            ->whereDeletePermission('capability');

        $this->assertNotOperator($taxonomyQueryBuilder);

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
    public function testNotWhereDeletePermissionWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereDeletePermission('capability_1')
            ->whereDeletePermission('capability_2');

        $this->assertNotOperator($taxonomyQueryBuilder);

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
    public function testNotWhereDeletePermissionWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereName('name')
            ->onlyDefault()
            ->whereDeletePermission('capability');

        $this->assertNotOperator($taxonomyQueryBuilder);

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
    public function testNotWhereDeletePermissionWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::make(operator: Operator::not)->whereDeletePermission('');
    }
}
