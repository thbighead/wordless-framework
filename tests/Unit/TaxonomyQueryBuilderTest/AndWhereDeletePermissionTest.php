<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use ReflectionException;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\AndComparison;

class AndWhereDeletePermissionTest extends TaxonomyBuilderTestCase
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereDeletePermission(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereDeletePermission('capability');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['delete_cap' => 'capability'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereDeletePermissionWhereSameAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereDeletePermission('capability')
            ->andWhereDeletePermission('capability');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['delete_cap' => 'capability'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereDeletePermissionWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereDeletePermission('capability_1')
            ->andWhereDeletePermission('capability_2');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['delete_cap' => 'capability_2'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereDeletePermissionWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereName('name')
            ->andOnlyDefault()
            ->andWhereDeletePermission('capability');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'delete_cap' => 'capability',
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}
