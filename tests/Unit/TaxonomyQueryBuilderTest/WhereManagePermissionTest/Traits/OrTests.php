<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereManagePermissionTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\OrComparison;

trait OrTests
{
    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testOrWhereManagePermission(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orWhereManagePermission('capability');

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

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
    public function testOrWhereManagePermissionWhereSameAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orWhereManagePermission('capability')
            ->orWhereManagePermission('capability');

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

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
    public function testOrWhereManagePermissionWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orWhereManagePermission('capability_1')
            ->orWhereManagePermission('capability_2');

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

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
    public function testOrWhereManagePermissionWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orWhereName('name')
            ->orOnlyDefault()
            ->orWhereManagePermission('capability');

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

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
    public function testOrWhereManagePermissionWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::getInstance()->orWhereManagePermission('');
    }
}
