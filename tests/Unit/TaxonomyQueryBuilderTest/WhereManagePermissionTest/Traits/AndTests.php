<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereManagePermissionTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\AndComparison;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testAndWhereManagePermission(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereManagePermission('capability');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['manage_cap' => 'capability'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testAndWhereManagePermissionWhereSameAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereManagePermission('capability')
            ->andWhereManagePermission('capability');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['manage_cap' => 'capability'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testAndWhereManagePermissionWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereManagePermission('capability_1')
            ->andWhereManagePermission('capability_2');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['manage_cap' => 'capability_2'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testAndWhereManagePermissionWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereName('name')
            ->andOnlyDefault()
            ->andWhereManagePermission('capability');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'manage_cap' => 'capability',
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
    public function testAndWhereManagePermissionWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::getInstance()->andWhereManagePermission('');
    }
}
